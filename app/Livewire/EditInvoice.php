<?php

namespace App\Livewire;

use App\Models\CustomerModel;
use App\Models\InvoiceProduct;
use App\Models\MakeInvoice as Invoice;
use App\Models\OtherCharge;
use App\Models\PaidInfo;
use App\Models\ProductModel;
use App\Models\TermsModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use NumberToWords\NumberToWords;

class EditInvoice extends Component
{
    public $totalAmount = 0;
    public $paidAmount = 0;
    public $invoice_date;
    public $po_no;
    public $addedCustomer;
    public $addedProducts = [];
    public $otherCharges = [];
    public $addedTerms = [];
    public $paidInfos = [];
    public $round_off;
    public $view;
    public $user;

    public Invoice $savedInvoice;

    #[On('customerAdded')]
    public function addCustomer(CustomerModel $customer) {
        $this->addedCustomer = $customer;
    }

    #[On('productAdded')]
    public function addProduct($data) {
        $index = count($this->addedProducts);
        $this->addedProducts[$index] = $data;
        $this->calculateTotal();
    }
    #[On('PaidInfoAdded')]
    public function addInfo($data) {
        $this->paidInfos = $data;
        $this->calculateTotal();
    }

    #[On('PaidInfoRemoved')]
    public function removeInfo($data) {
        $this->paidInfos = $data;
        $this->calculateTotal();
    }

    #[On('otherChargesAdded')]
    public function addCharges($data) {
        $this->otherCharges = $data;
        if($this->otherCharges['is_taxable']) {
            $other_charge_amount = (int) $this->otherCharges['other_charge_amount'];
            $gst_percentage = (int) $this->otherCharges['gst_percentage'];    
            $gst_amount = ($other_charge_amount * $gst_percentage) / (100);
            $this->otherCharges['gst_amount'] = $gst_amount;
        }
        $this->calculateTotal();
    }

    public function roundOff($value) {
        $this->round_off = $value;
        $this->calculateTotal();
    }

    #[On('termsAdded')]
    public function addTerms($data) {
        $terms = TermsModel::whereIn('id',$data ?? [])->get();
        foreach($terms as $term) {
            $this->addedTerms[$term->id] = $term;
        }
    }

    public function removeCustomer() {
        $this->reset('addedCustomer');
    }
    public function removeCharges() {
        $this->reset('otherCharges');
        $this->calculateTotal();
    }

   public function mount(Invoice $invoice) {
        $invoice->load(['otherCharge', 'customer', 'terms','invoiceProducts' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }]);
        
        $this->savedInvoice = $invoice;
        $this->user = auth()->user();
        $this->invoice_date = $invoice->invoice_date;
        $this->po_no = $invoice->po_no;
        $this->round_off = $invoice->round_off ? true : false;
        $this->addProducts($invoice->invoiceProducts);
        $this->addedCustomer = $invoice->customer;
        $this->addedTerms = $invoice->terms->keyBy('id');
        if($invoice->otherCharge) {
            $this->otherCharges = [ 
                'other_charge_label' => $invoice?->otherCharge->label, 
                'other_charge_amount' => $invoice?->otherCharge->amount, 
                'gst_percentage' => $invoice?->otherCharge->gst_percentage, 
                'gst_amount' => $invoice?->otherCharge->gst_amount, 
                'is_taxable' => $invoice?->otherCharge->is_taxable,
                'other_charge_id' => $invoice->otherCharge->id
            ];
        }
        $this->totalAmount = $invoice->balance_due;
        $this->paidAmount = $invoice->paid_amount;
    }

    public function addProducts($products) {
        foreach($products as $invoiceProduct) {
            $product = ProductModel::firstWhere('id', $invoiceProduct->product_id)->toArray();
            $data = ['product' => $product, 'quantity' => $invoiceProduct->quantity, 'price' => $invoiceProduct->price, 'description' => $invoiceProduct->description];
            $this->addProduct($data, $invoiceProduct->sort_order);
        }
    }
    
    public function calculateTotal() {
        $this->totalAmount = 0;
        foreach($this->addedProducts as $product) {
            $this->totalAmount += (int) $product['quantity'] * (int) $product['price'];
        }
        if($this->otherCharges) {
            $this->totalAmount += (int) $this->otherCharges['other_charge_amount'];
            if($this->otherCharges['is_taxable']) {
                $this->totalAmount += $this->otherCharges['gst_amount'];
            } 
        }
        $this->paidAmount = 0;
        foreach($this->paidInfos as $info) {
            $this->paidAmount += $info['amount'];
        }
        $this->totalAmount -= $this->paidAmount;
        if($this->round_off) {
            $this->totalAmount = round($this->totalAmount);
        }
    }

    public function removeProduct($index) {
        unset($this->addedProducts[$index]);
        $this->addedProducts = array_values($this->addedProducts);
        $this->calculateTotal();
    }
    
    public function removeTerms($index) {
        unset($this->addedTerms[$index]);
        $this->dispatch('termRemoved', $index);
    }

    public function updateProductOrder($orders) {
        $tempProducts = [];
        foreach($orders as $index => $order) {
            $tempProducts[$index] = $this->addedProducts[(int) $order['value']];
        }
        $this->addedProducts = $tempProducts;
    }

    public function generatePdf() {
        $this->validate([
            'addedCustomer' => 'required',
            'addedProducts' => 'required',
        ], [
            'addedCustomer' => 'Please add cutomer',
            'addedProducts' => 'Please add product'
        ]);

        $products = $this->addedProducts;
        $customer = $this->addedCustomer;
        $terms = $this->addedTerms;
        $charges = $this->otherCharges;
        $user = $this->user;
        $date = $this->invoice_date;
        $totalAmount = $this->totalAmount;
        $paidAmount = $this->paidAmount;
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $amountInWord = $numberTransformer->toWords($this->totalAmount);
        $invoiceNumber = $this->savedInvoice->invoice_no;
        $poNo = $this->po_no;
        $fileName = 'Inv_'.$invoiceNumber.'.pdf';
        $pdf = Pdf::loadView('make-invoice\pdf', compact('products', 'customer', 'terms', 'charges', 'user', 'date', 'totalAmount', 'amountInWord', 'paidAmount', 'invoiceNumber', 'poNo'));
        return response()->streamDownload(function () use ($pdf) {
           echo  $pdf->stream();
        }, $fileName);
    }

    public function updateInvoice() {
        $this->validate([
            'addedCustomer' => 'required',
            'addedProducts' => 'required',
        ], [
            'addedCustomer' => 'Please add cutomer',
            'addedProducts' => 'Please add product'
        ]);

        $products = $this->addedProducts;
        $customer = $this->addedCustomer;
        $terms = $this->addedTerms;
        $charges = $this->otherCharges;
        $user = $this->user;
        $date = $this->invoice_date;
        $totalAmount = $this->totalAmount;
        $paidAmount = $this->paidAmount;
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $amountInWord = $numberTransformer->toWords($this->totalAmount);
    
        $this->savedInvoice->customer_id = $customer?->id;
        $this->savedInvoice->total_amount = $totalAmount + $paidAmount;
        $this->savedInvoice->paid_amount = $paidAmount;
        $this->savedInvoice->balance_due = $totalAmount;
        $this->savedInvoice->invoice_date = $date;
        $this->savedInvoice->po_no = $this->po_no;
        $this->savedInvoice->round_off = $this->round_off ? 1: 0;
        $this->savedInvoice->save();

        $this->savedInvoice->invoiceProducts()->delete();
        
        foreach($products as $key => $product) {
            $invoiceProduct = new InvoiceProduct();
            $invoiceProduct->product_id = $product['product']['id'];
            $invoiceProduct->invoice_id = $this->savedInvoice->id;
            $invoiceProduct->quantity = $product['quantity'];
            $invoiceProduct->description = $product['description'];
            $invoiceProduct->sort_order = $key;
            $invoiceProduct->price = $product['price'];
            $invoiceProduct->save();
        }

        $otherCharge = new OtherCharge();
        if(!empty($charges)) {
            if(isset($charges['other_charge_id'])) {
                $otherCharge = OtherCharge::find($charges['other_charge_id']);
            } else {
                $this->savedInvoice->otherCharge()->delete();
            }
            $otherCharge->label = $charges['other_charge_label'];
            $otherCharge->amount = $charges['other_charge_amount'];
            $otherCharge->is_taxable = $charges['is_taxable'] ? 1 : 0;
            $otherCharge->gst_percentage = $charges['gst_percentage'] ?? null;
            $otherCharge->gst_amount = $charges['gst_amount'] ?? null;
            $otherCharge->chargeable_id = $this->savedInvoice->id;;
            $otherCharge->chargeable_type = Invoice::class;
            $otherCharge->save();
        }
    

        $this->savedInvoice->paidInfos()->delete();
        foreach($this->paidInfos as $info) {
           $paidInfo = new PaidInfo();
           $paidInfo->amount = $info['amount'];
           $paidInfo->paid_date = $info['date'];
           $paidInfo->notes = $info['notes'];
           $paidInfo->info_id = $this->savedInvoice->id;;
           $paidInfo->info_type = Invoice::class;
           $paidInfo->save();
        }

        if($terms) {
            $termIds = $terms->pluck('id')->toArray();
            $this->savedInvoice->terms()->sync($termIds);
        }
        $route = route('make-invoice.edit', $this->savedInvoice->id);
        $this->dispatch('invoiceUpdated', $route);

    }

    public function render()
    {
        return view('livewire.edit-invoice');
    }
}

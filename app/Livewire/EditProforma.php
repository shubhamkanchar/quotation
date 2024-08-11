<?php

namespace App\Livewire;

use App\Models\CustomerModel;
use App\Models\MakeProformaInvoice;
use App\Models\OtherCharge;
use App\Models\PaidInfo;
use App\Models\ProductModel;
use App\Models\ProformaInvoiceProduct;
use App\Models\TermsModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use NumberToWords\NumberToWords;

class EditProforma extends Component
{
    public float $totalAmount = 0;
    public $paidAmount = 0;
    public $proforma_date;
    public $due_date;
    public $po_no;
    public $addedCustomer;
    public $addedProducts = [];
    public $otherCharges = [];
    public $addedTerms = [];
    public $paidInfos = [];
    public $round_off;
    public $view;
    public $user;

    public MakeProformaInvoice $savedProformaInvoice;
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

    public function mount(MakeProformaInvoice $invoice) {
        $invoice->load(['otherCharge', 'customer', 'terms','proformaInvoiceProducts' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }]);
        
        $this->savedProformaInvoice = $invoice;
        $this->user = auth()->user();
        $this->proforma_date = $invoice->proforma_invoice_date;
        $this->due_date = $invoice->due_date;
        $this->po_no = $invoice->po_no;
        $this->round_off = $invoice->round_off ? true : false;
        $this->addProducts($invoice->proformaInvoiceProducts);
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
        $this->totalAmount = (float) $invoice->balance_due;
        $this->paidAmount = $invoice->paid_amount;
    }

    public function addProducts($products) {
        foreach($products as $invoiceProduct) {
            $product = ProductModel::withTrashed()->firstWhere('id', $invoiceProduct->product_id)->toArray();
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
        $date = $this->proforma_date;
        $dueDate = $this->due_date;
        $poNo = $this->po_no;
        $totalAmount = $this->totalAmount;
        $paidAmount = $this->paidAmount;
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en'); // 'en' for English
        $amountInWord = $numberTransformer->toWords($this->totalAmount);
        $lastInvoice = MakeProformaInvoice::query()->orderBy('proforma_invoice_no', 'desc')->first();
        $proformaInvoiceNumber = $this->savedProformaInvoice->proforma_invoice_no;
    
        $pdf = Pdf::loadView('make-proforma\pdf', compact('products', 'customer', 'terms', 'charges', 'user', 'date', 'poNo','dueDate','totalAmount', 'amountInWord', 'paidAmount', 'proformaInvoiceNumber'));
        return response()->streamDownload(function () use ($pdf) {
           echo  $pdf->stream();
        }, 'pi_invoice.pdf');
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
        $date = $this->proforma_date;
        $date = $this->proforma_date;
        $dueDate = $this->due_date;
        $poNo = $this->po_no;
        $totalAmount = $this->totalAmount;
        $paidAmount = $this->paidAmount;
       
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $amountInWord = $numberTransformer->toWords($this->totalAmount);
    
        $this->savedProformaInvoice->customer_id = $customer?->id;
        $this->savedProformaInvoice->total_amount = $totalAmount + $paidAmount;
        $this->savedProformaInvoice->paid_amount = $paidAmount;
        $this->savedProformaInvoice->balance_due = $totalAmount;
        $this->savedProformaInvoice->proforma_invoice_date = $date;
        $this->savedProformaInvoice->due_date = $dueDate;
        $this->savedProformaInvoice->po_no = $this->po_no;
        $this->savedProformaInvoice->round_off = $this->round_off ? 1: 0;
        $this->savedProformaInvoice->save();

        $this->savedProformaInvoice->proformaInvoiceProducts()->delete();
        
        foreach($products as $key => $product) {
            $invoiceProduct = new ProformaInvoiceProduct();
            $invoiceProduct->product_id = $product['product']['id'];
            $invoiceProduct->proforma_invoice_id = $this->savedProformaInvoice->id;
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
                $this->savedProformaInvoice->otherCharge()->delete();
            }
            $otherCharge->label = $charges['other_charge_label'];
            $otherCharge->amount = $charges['other_charge_amount'];
            $otherCharge->is_taxable = $charges['is_taxable'] ? 1 : 0;
            $otherCharge->gst_percentage = $charges['gst_percentage'] ?? null;
            $otherCharge->gst_amount = $charges['gst_amount'] ?? null;
            $otherCharge->chargeable_id = $this->savedProformaInvoice->id;;
            $otherCharge->chargeable_type = MakeProformaInvoice::class;
            $otherCharge->save();
        }
    

        $this->savedProformaInvoice->paidInfos()->delete();
        foreach($this->paidInfos as $info) {
           $paidInfo = new PaidInfo();
           $paidInfo->amount = $info['amount'];
           $paidInfo->paid_date = $info['date'];
           $paidInfo->notes = $info['notes'];
           $paidInfo->info_id = $this->savedProformaInvoice->id;;
           $paidInfo->info_type = MakeProformaInvoice::class;
           $paidInfo->save();
        }

        if($terms) {
            $termIds = $terms->pluck('id')->toArray();
            $this->savedProformaInvoice->terms()->sync($termIds);
        }

        $this->dispatch('proformaInvoiceUpdated');

    }

    public function render()
    {
        return view('livewire.edit-proforma');
    }
}

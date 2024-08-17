<?php

namespace App\Livewire;

use App\Models\CustomerModel;
use App\Models\InvoiceProduct;
use App\Models\MakeInvoice as Invoice;
use App\Models\OtherCharge;
use App\Models\PaidInfo;
use App\Models\TermsModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use NumberToWords\NumberToWords;

class MakeInvoice extends Component
{
    public $totalAmount = 0;
    public $paidAmount = 0;
    public $invoice_date;
    public $due_date;
    public $po_no = null;
    public $addedCustomer;
    public $addedProducts = [];
    public $otherCharges = [];
    public $addedTerms = [];
    public $paidInfos = [];
    public $round_off;
    public $view;
    public $user;
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
            $other_charge_amount = (float) $this->otherCharges['other_charge_amount'];
            $gst_percentage = (float) $this->otherCharges['gst_percentage'];    
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

    public function mount() {
        $this->user = auth()->user();
        $this->invoice_date = Carbon::now()->format('Y-m-d');
    }
    public function calculateTotal() {
        $this->totalAmount = 0;
        foreach($this->addedProducts as $product) {
            $this->totalAmount += (float) $product['quantity'] * (float) $product['price'];
        }
        if($this->otherCharges) {
            $this->totalAmount += (float) $this->otherCharges['other_charge_amount'];
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
            'invoice_date' => 'required',
            'addedCustomer' => 'required',
            'addedProducts' => 'required',
        ], [
            'invoice_date' => 'Please select In  customer',
            'addedCustomer' => 'Please add customer',
            'addedProducts' => 'Please add product'
        ]);

        $products = $this->addedProducts;
        $customer = $this->addedCustomer;
        $terms = $this->addedTerms;
        $charges = $this->otherCharges;
        $user = $this->user;
        $date = $this->invoice_date;
        $dueDate = $this->due_date;
        $totalAmount = $this->totalAmount;
        $paidAmount = $this->paidAmount;
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $amountInWord = $numberTransformer->toWords($this->totalAmount);
        $lastInvoice = Invoice::query()->orderBy('invoice_no', 'desc')->first();
        $invoice = new Invoice();
        $invoice->customer_id = $customer?->id;
        $invoice->invoice_no = !is_null($lastInvoice?->invoice_no) ? ($lastInvoice?->invoice_no +1) : 1;
        $invoice->total_amount = $totalAmount + $paidAmount;
        $invoice->paid_amount = $paidAmount;
        $invoice->balance_due = $totalAmount;
        $invoice->invoice_date = $date;
        $invoice->po_no = $this->po_no;
        $invoice->due_date = $dueDate;
        $invoice->created_by = $this->user->id;
        $invoice->business_id = $this->user->business->id;
        $invoice->round_off = $this->round_off ? 1: 0;
        $invoice->save();
        $invoiceNumber = $invoice->invoice_no;
    
        foreach($products as $key => $product) {
            $invoiceProduct = new InvoiceProduct();
            $invoiceProduct->product_id = $product['product']['id'];
            $invoiceProduct->invoice_id = $invoice->id;
            $invoiceProduct->quantity = $product['quantity'];
            $invoiceProduct->description = $product['description'];
            $invoiceProduct->sort_order = $key;
            $invoiceProduct->price = $product['price'];
            $invoiceProduct->save();
        }

        $otherCharge = new OtherCharge();
        if(!empty($charges)) {
            $otherCharge->label = $charges['other_charge_label'];
            $otherCharge->amount = $charges['other_charge_amount'];
            $otherCharge->is_taxable = $charges['is_taxable'] ? 1 : 0;
            $otherCharge->gst_percentage = $charges['gst_percentage'] ?? null;
            $otherCharge->gst_amount = $charges['gst_amount'] ?? null;
            $otherCharge->chargeable_id = $invoice->id;;
            $otherCharge->chargeable_type = Invoice::class;
            $otherCharge->save();
        }

        foreach($this->paidInfos as $info) {
           $paidInfo = new PaidInfo();
           $paidInfo->amount = $info['amount'];
           $paidInfo->paid_date = $info['date'];
           $paidInfo->notes = $info['notes'];
           $paidInfo->info_id = $invoice->id;;
           $paidInfo->info_type = Invoice::class;
           $paidInfo->save();
        }

        $termIds = array_keys($terms);
        $invoice->terms()->sync($termIds);
        $route = route('make-invoice.edit', $invoice->uuid);
        $this->dispatch('invoiceCreated', $route);
    }
    
    public function render()
    {
        return view('livewire.make-invoice');
    }
}

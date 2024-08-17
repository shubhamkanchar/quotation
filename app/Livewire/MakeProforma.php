<?php

namespace App\Livewire;

use App\Models\CustomerModel;
use App\Models\MakeProformaInvoice;
use App\Models\OtherCharge;
use App\Models\PaidInfo;
use App\Models\ProformaInvoiceProduct;
use App\Models\TermsModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use NumberToWords\NumberToWords;

class MakeProforma extends Component
{
    public $totalAmount = 0;
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

    public function mount() {
        $this->user = auth()->user();
        $this->proforma_date = Carbon::now()->format('Y-m-d');
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
            'proforma_date' => 'required',
            'addedCustomer' => 'required',
            'addedProducts' => 'required',
        ], [
            'proforma_date' => 'Please select proforma invoice date',
            'addedCustomer' => 'Please add customer',
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
        $proformaInvoice = new MakeProformaInvoice();
        $proformaInvoice->customer_id = $customer?->id;
        $proformaInvoice->proforma_invoice_no = !is_null($lastInvoice?->proforma_invoice_no) ? ($lastInvoice?->proforma_invoice_no +1) : 1;
        $proformaInvoice->total_amount = $totalAmount + $paidAmount;
        $proformaInvoice->paid_amount = $paidAmount;
        $proformaInvoice->balance_due = $totalAmount;
        $proformaInvoice->proforma_invoice_date = $date;
        $proformaInvoice->due_date = $dueDate;
        $proformaInvoice->po_no = $poNo;
        $proformaInvoice->due_date = $dueDate;
        $proformaInvoice->created_by = $this->user->id;
        $proformaInvoice->business_id = $this->user->business->id;
        $proformaInvoice->round_off = $this->round_off ? 1: 0;
        $proformaInvoice->save();
        $proformaInvoiceNumber = $proformaInvoice->proforma_invoice_no;
    
        foreach($products as $key => $product) {
            $invoiceProduct = new ProformaInvoiceProduct();
            $invoiceProduct->product_id = $product['product']['id'];
            $invoiceProduct->proforma_invoice_id = $proformaInvoice->id;
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
            $otherCharge->chargeable_id = $proformaInvoice->id;;
            $otherCharge->chargeable_type = MakeProformaInvoice::class;
            $otherCharge->save();
        }

        foreach($this->paidInfos as $info) {
           $paidInfo = new PaidInfo();
           $paidInfo->amount = $info['amount'];
           $paidInfo->paid_date = $info['date'];
           $paidInfo->notes = $info['notes'];
           $paidInfo->info_id = $proformaInvoice->id;;
           $paidInfo->info_type = MakeProformaInvoice::class;
           $paidInfo->save();
        }

        $termIds = array_keys($terms);
        $proformaInvoice->terms()->sync($termIds);
        $route = route('make-proforma-invoice.edit', $proformaInvoice->uuid);
        $this->dispatch('ProformaInvoiceCreated', $route);

        $pdf = Pdf::loadView('make-proforma\pdf', compact('products', 'customer', 'terms', 'charges', 'user', 'date', 'poNo','dueDate','totalAmount', 'amountInWord', 'paidAmount', 'proformaInvoiceNumber'));
    }
    public function render()
    {
        return view('livewire.make-proforma');
    }
}

<?php

namespace App\Livewire;

use App\Models\CustomerModel;
use App\Models\MakeQuotation as Quotation;
use App\Models\OtherCharge;
use App\Models\QuotationProducts;
use App\Models\TermsModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class MakeQuotation extends Component
{
    public $totalAmount = 0;
    public $quotation_date;
    public $addedCustomer;
    public $addedProducts = [];
    public $otherCharges = [];
    public $addedTerms = [];
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
        $this->quotation_date = Carbon::now()->format('Y-m-d');
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
        $date = $this->quotation_date;
        $totalAmount = $this->totalAmount;
        $lastQuotation = Quotation::query()->orderBy('quotation_no', 'desc')->first();
        $quotation = new Quotation();
        $quotation->customer_id = $customer?->id;
        $quotation->quotation_no = !is_null($lastQuotation?->quotation_no) ? ($lastQuotation?->quotation_no +1) : 1;
        $quotation->total_amount = $totalAmount;
        $quotation->quotation_date = $date;
        $quotation->round_off = $this->round_off ? 1: 0;
        $quotation->save();
        $quotationNumber = $quotation->quotation_no;
    
        foreach($products as $key => $product) {
            $quotationProduct = new QuotationProducts();
            $quotationProduct->product_id = $product['product']['id'];
            $quotationProduct->quotation_id = $quotation->id;
            $quotationProduct->quantity = $product['quantity'];
            $quotationProduct->description = $product['description'];
            $quotationProduct->sort_order = $key;
            $quotationProduct->price = $product['price'];
            $quotationProduct->save();
        }

        $otherCharge = new OtherCharge();
        if(!empty($charges)) {
            $otherCharge->label = $charges['other_charge_label'];
            $otherCharge->amount = $charges['other_charge_amount'];
            $otherCharge->is_taxable = $charges['is_taxable'] ? 1 : 0;
            $otherCharge->gst_percentage = $charges['gst_percentage'] ?? null;
            $otherCharge->gst_amount = $charges['gst_amount'] ?? null;
            $otherCharge->chargeable_id = $quotation->id;;
            $otherCharge->chargeable_type = Quotation::class;
            $otherCharge->save();
        }
        
        $termIds = array_keys($terms);
        $quotation->terms()->sync($termIds);
        $route = route('make-quotation.edit', $quotation->id);
        $this->dispatch('quotationCreated', $route);
    }

    public function render()
    {
        return view('livewire.make-quotation');
    }
}

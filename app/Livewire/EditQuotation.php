<?php

namespace App\Livewire;

use App\Models\CustomerModel;
use App\Models\MakeQuotation as Quotation;
use App\Models\OtherCharge;
use App\Models\ProductModel;
use App\Models\QuotationProducts;
use App\Models\TermsModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class EditQuotation extends Component
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

    public Quotation $savedQuotation;
    #[On('customerAdded')]
    public function addCustomer(CustomerModel $customer) {
        $this->addedCustomer = $customer;
    }

    #[On('productAdded')]
    public function addProduct($data, $index = null) {
        $index = $index ?? count($this->addedProducts);
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

    public function mount(Quotation $quotation) {
        $quotation->load(['otherCharge', 'customer', 'terms','quotationProducts' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }]);
        
        $this->savedQuotation = $quotation;
        $this->user = auth()->user();
        $this->quotation_date = $quotation->quotation_date;
        $this->round_off = $quotation->round_off ? true : false;
        $this->addProducts($quotation->quotationProducts);
        $this->addedCustomer = $quotation->customer;
        $this->addedTerms = $quotation->terms->keyBy('id');
        $this->totalAmount = $quotation->total_amount;
        if($quotation->otherCharge) {
            $this->otherCharges = [ 
                'other_charge_label' => $quotation?->otherCharge->label, 
                'other_charge_amount' => $quotation?->otherCharge->amount, 
                'gst_percentage' => $quotation?->otherCharge->gst_percentage, 
                'gst_amount' => $quotation?->otherCharge->gst_amount, 
                'is_taxable' => $quotation?->otherCharge->is_taxable,
                'other_charge_id' => $quotation->otherCharge->id
            ];
        }
    }

    public function addProducts($products) {
        foreach($products as $quotationProduct) {
            $product = ProductModel::withTrashed()->firstWhere('id', $quotationProduct->product_id)->toArray();
            $data = ['product' => $product, 'quantity' => $quotationProduct->quantity, 'price' => $quotationProduct->price, 'description' => $quotationProduct->description];
            $this->addProduct($data, $quotationProduct->sort_order);
        }
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
            'quotation_date' => 'required',
            'addedCustomer' => 'required',
            'addedProducts' => 'required',
        ], [
            'quotation_date' => 'Please select the Quotation date',
            'addedCustomer' => 'Please add customer',
            'addedProducts' => 'Please add product'
        ]);

        $products = $this->addedProducts;
        $customer = $this->addedCustomer;
        $terms = $this->addedTerms;
        $charges = $this->otherCharges;
        $user = $this->user;
        $date = $this->quotation_date;
        $totalAmount = $this->totalAmount;
        $quotationNumber = $this->savedQuotation->quotation_no;

        $fileName = 'quotation_'.$quotationNumber.'.pdf';
        $pdf = PDF::loadView('make-quotation.pdf', compact('products', 'customer', 'terms', 'charges', 'user', 'date', 'totalAmount', 'quotationNumber'));
        return response()->streamDownload(function () use ($pdf) {
           echo  $pdf->stream();
        }, $fileName);
    }

    public function updateQuotation() {
        $this->validate([
            'quotation_date' => 'required',
            'addedCustomer' => 'required',
            'addedProducts' => 'required',
        ], [
            'quotation_date' => 'Please select the Quotation date',
            'addedCustomer' => 'Please add customer',
            'addedProducts' => 'Please add product'
        ]);

        $products = $this->addedProducts;
        $customer = $this->addedCustomer;
        $terms = $this->addedTerms;
        $charges = $this->otherCharges;
        $user = $this->user;
        $date = $this->quotation_date;
        $totalAmount = $this->totalAmount;
        $this->savedQuotation->customer_id = $customer?->id;
        $this->savedQuotation->created_by = $this->user->id;
        $this->savedQuotation->business_id = $this->user->business->id;
        $this->savedQuotation->total_amount = $totalAmount;
        $this->savedQuotation->quotation_date = $date;
        $this->savedQuotation->round_off = $this->round_off ? 1: 0;
        $this->savedQuotation->update();
        $quotationNumber = $this->savedQuotation->quotation_no;
        $this->savedQuotation->quotationProducts()->delete();
        foreach($products as $key => $product) {
            $quotationProduct = new QuotationProducts();
            $quotationProduct->product_id = $product['product']['id'];
            $quotationProduct->quotation_id = $this->savedQuotation->id;
            $quotationProduct->quantity = $product['quantity'];
            $quotationProduct->sort_order = $key;
            $quotationProduct->price = $product['price'];
            $quotationProduct->save();
        }

        $otherCharge = new OtherCharge();
        if(!empty($charges)) {
            if(isset($charges['other_charge_id'])) {
                $otherCharge = OtherCharge::find($charges['other_charge_id']);
            } else {
                $this->savedPurchaseOrder->otherCharge()->delete();
            }
            $otherCharge->label = $charges['other_charge_label'];
            $otherCharge->amount = $charges['other_charge_amount'];
            $otherCharge->is_taxable = $charges['is_taxable'] ? 1 : 0;
            $otherCharge->gst_percentage = $charges['gst_percentage'] ?? null;
            $otherCharge->gst_amount = $charges['gst_amount'] ?? null;
            $otherCharge->chargeable_id = $this->savedQuotation->id;;
            $otherCharge->chargeable_type = Quotation::class;
            $otherCharge->save();
        }
        
        if($terms) {
            $termIds = $terms->pluck('id')->toArray();
            $this->savedQuotation->terms()->sync($termIds);
        }
        
        $fileName = 'quotation_'.$quotationNumber.'.pdf';
        $this->dispatch('quotationUpdated');
    }

    public function render()
    {
        return view('livewire.edit-quotation');
    }
}

    

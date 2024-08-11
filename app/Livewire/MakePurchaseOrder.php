<?php

namespace App\Livewire;

use App\Models\CustomerModel;
use App\Models\MakePurchaseOrder as PurchaseOrder;
use App\Models\OtherCharge;
use App\Models\PurchaseOrderProduct;
use App\Models\TermsModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class MakePurchaseOrder extends Component
{
    public $totalAmount = 0;
    public $purchase_order_date;
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
        $this->purchase_order_date = Carbon::now()->format('Y-m-d');
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
        $date = $this->purchase_order_date;
        $totalAmount = $this->totalAmount;
        $totalAmount = $this->totalAmount;
        $lastPurchase = PurchaseOrder::query()->orderBy('purchase_order_no', 'desc')->first();
        $purchaseOrder = new PurchaseOrder();
        $purchaseOrder->customer_id = $customer?->id;
        $purchaseOrder->purchase_order_no = !is_null($lastPurchase?->purchase_order_no) ? ($lastPurchase?->purchase_order_no +1) : 1;
        $purchaseOrder->total_amount = $totalAmount;
        $purchaseOrder->purchase_date = $date;
        $purchaseOrder->created_by = $this->user->id;
        $purchaseOrder->business_id = $this->user->business->id;
        $purchaseOrder->round_off = $this->round_off ? 1: 0;
        $purchaseOrder->save();
        $purchaseNumber = $purchaseOrder->purchase_order_no;
    
        foreach($products as $key => $product) {
            $PurchaseOrderProduct = new PurchaseOrderProduct();
            $PurchaseOrderProduct->product_id = $product['product']['id'];
            $PurchaseOrderProduct->purchase_order_id = $purchaseOrder->id;
            $PurchaseOrderProduct->quantity = $product['quantity'];
            $PurchaseOrderProduct->description = $product['description'];
            $PurchaseOrderProduct->sort_order = $key;
            $PurchaseOrderProduct->price = $product['price'];
            $PurchaseOrderProduct->save();
        }

        $otherCharge = new OtherCharge();
        if(!empty($charges)) {
            $otherCharge->label = $charges['other_charge_label'];
            $otherCharge->amount = $charges['other_charge_amount'];
            $otherCharge->is_taxable = $charges['is_taxable'] ? 1 : 0;
            $otherCharge->gst_percentage = $charges['gst_percentage'] ?? null;
            $otherCharge->gst_amount = $charges['gst_amount'] ?? null;
            $otherCharge->chargeable_id = $purchaseOrder->id;;
            $otherCharge->chargeable_type = PurchaseOrder::class;
            $otherCharge->save();
        }
        
        $termIds = array_keys($terms);
        $purchaseOrder->terms()->sync($termIds);
        $route = route('make-purchase-order.edit', $purchaseOrder->uuid);
        $this->dispatch('purchaseOrderCreated', $route);
    }
    public function render()
    {
        return view('livewire.make-purchase-order');
    }
}

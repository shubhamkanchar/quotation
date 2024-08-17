<?php

namespace App\Livewire;

use App\Models\CustomerModel;
use App\Models\MakePurchaseOrder as PurchaseOrder;
use App\Models\OtherCharge;
use App\Models\ProductModel;
use App\Models\PurchaseOrderProduct;
use App\Models\TermsModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class EditPurchaseOrder extends Component
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

    public PurchaseOrder $savedPurchaseOrder;
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

    public function mount(PurchaseOrder $purchaseOrder) {
        $purchaseOrder->load(['otherCharge', 'customer', 'terms','purchaseOrderProducts' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }]);
        
        $this->savedPurchaseOrder = $purchaseOrder;
        $this->user = auth()->user();
        $this->purchase_order_date = $purchaseOrder->purchase_date;
        $this->round_off = $purchaseOrder->round_off ? true : false;
        $this->addProducts($purchaseOrder->purchaseOrderProducts);
        $this->addedCustomer = $purchaseOrder->customer;
        $this->addedTerms = $purchaseOrder->terms->keyBy('id');
        $this->totalAmount = $purchaseOrder->total_amount;
        if($purchaseOrder->otherCharge) {
            $this->otherCharges = [ 
                'other_charge_label' => $purchaseOrder?->otherCharge->label, 
                'other_charge_amount' => $purchaseOrder?->otherCharge->amount, 
                'gst_percentage' => $purchaseOrder?->otherCharge->gst_percentage, 
                'gst_amount' => $purchaseOrder?->otherCharge->gst_amount, 
                'is_taxable' => $purchaseOrder?->otherCharge->is_taxable,
                'other_charge_id' => $purchaseOrder->otherCharge->id
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
            'purchase_order_date' => 'required',
            'addedCustomer' => 'required',
            'addedProducts' => 'required',
        ], [
            'purchase_order_date' => 'Please select purchase order date',
            'addedCustomer' => 'Please add customer',
            'addedProducts' => 'Please add product'
        ]);

        $products = $this->addedProducts;
        $customer = $this->addedCustomer;
        $terms = $this->addedTerms;
        $charges = $this->otherCharges;
        $user = $this->user;
        $date = $this->purchase_order_date;
        $totalAmount = $this->totalAmount;
        $purchaseNumber = $this->savedPurchaseOrder->purchase_order_no;
        $fileName = 'PO_'.$purchaseNumber.'.pdf';
        $pdf = PDF::loadView('make-purchase-order.pdf', compact('products', 'customer', 'terms', 'charges', 'user', 'date', 'totalAmount', 'purchaseNumber'));
        return response()->streamDownload(function () use ($pdf) {
           echo  $pdf->stream();
        }, $fileName);
    }

    public function updatePurchaseOrder() {
        $this->validate([
            'purchase_order_date' => 'required',
            'addedCustomer' => 'required',
            'addedProducts' => 'required',
        ], [
            'purchase_order_date' => 'Please select purchase order date',
            'addedCustomer' => 'Please add customer',
            'addedProducts' => 'Please add product'
        ]);

        $products = $this->addedProducts;
        $customer = $this->addedCustomer;
        $terms = $this->addedTerms;
        $charges = $this->otherCharges;
        $date = $this->purchase_order_date;
        $totalAmount = $this->totalAmount;
        
        $this->savedPurchaseOrder->customer_id = $customer?->id;
        $this->savedPurchaseOrder->total_amount = $totalAmount;
        $this->savedPurchaseOrder->purchase_date = $date;
        $this->savedPurchaseOrder->round_off = $this->round_off ? 1: 0;
        $this->savedPurchaseOrder->created_by = $this->user->id;
        $this->savedPurchaseOrder->business_id = $this->user->business->id;
        $this->savedPurchaseOrder->save();
        $purchaseNumber = $this->savedPurchaseOrder->purchase_order_no;
        $this->savedPurchaseOrder->purchaseOrderProducts()->delete();
        foreach($products as $key => $product) {
            $PurchaseOrderProduct = new PurchaseOrderProduct();
            $PurchaseOrderProduct->product_id = $product['product']['id'];
            $PurchaseOrderProduct->purchase_order_id = $this->savedPurchaseOrder->id;
            $PurchaseOrderProduct->quantity = $product['quantity'];
            $PurchaseOrderProduct->description = $product['description'];
            $PurchaseOrderProduct->sort_order = $key;
            $PurchaseOrderProduct->price = $product['price'];
            $PurchaseOrderProduct->save();
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
            $otherCharge->chargeable_id = $this->savedPurchaseOrder->id;;
            $otherCharge->chargeable_type = PurchaseOrder::class;
            $otherCharge->save();
        }
        
        if($terms) {
            $termIds = $terms->pluck('id')->toArray();
            $this->savedPurchaseOrder->terms()->sync($termIds);
        }
       
        $this->dispatch('purchaseOrderUpdated');

    }

    public function render()
    {
        return view('livewire.edit-purchase-order');
    }
}

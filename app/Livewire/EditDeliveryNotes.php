<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CustomerModel;
use App\Models\DeliveryProduct;
use App\Models\MakeDeliveryNote;
use App\Models\ProductModel;
use App\Models\TermsModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\On;

class EditDeliveryNotes extends Component
{
    public $totalAmount = 0;
    public $delivery_date;
    public $reference_no = '';
    public $delivery_no = '';
    public $addedCustomer;
    public $addedProducts = [];
    public $otherCharges = [];
    public $addedTerms = [];
    public $round_off;
    public $view;
    public $user;

    public MakeDeliveryNote $savedDeliveryNote;
    #[On('customerAdded')]
    public function addCustomer(CustomerModel $customer) {
        $this->addedCustomer = $customer;
    }

    #[On('productAdded')]
    public function addProduct($data) {
        $index = count($this->addedProducts);
        $this->addedProducts[$index] = $data;
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

    public function mount(MakeDeliveryNote $deliveryNote) {
        $this->user = auth()->user();
        $deliveryNote->load(['customer', 'terms','deliveryProducts' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }]);
        
        $this->savedDeliveryNote = $deliveryNote;
        $this->delivery_date = $deliveryNote->delivery_date;
        $this->reference_no = $deliveryNote->reference_no;
        $this->addProducts($deliveryNote->deliveryProducts);
        $this->addedCustomer = $deliveryNote->customer;
        $this->addedTerms = $deliveryNote->terms->keyBy('id');
    }

    public function addProducts($products) {
        foreach($products as $deliveryProduct) {
            $product = ProductModel::withTrashed()->firstWhere('id', $deliveryProduct->product_id)->toArray();
            $data = ['product' => $product, 'quantity' => $deliveryProduct->quantity, 'price' => $deliveryProduct->price, 'description' => $deliveryProduct->description];
            $this->addProduct($data, $deliveryProduct->sort_order);
        }
    }

    public function removeProduct($index) {
        unset($this->addedProducts[$index]);
        $this->addedProducts = array_values($this->addedProducts);
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

    public function updateDeliveryNote() {
        $this->validate([
            'delivery_date' => 'required',
            'addedCustomer' => 'required',
            'addedProducts' => 'required',
        ], [
            'delivery_date' => 'Please select delivery date',
            'addedCustomer' => 'Please add customer',
            'addedProducts' => 'Please add product'
        ]);

        $products = $this->addedProducts;
        $customer = $this->addedCustomer;
        $terms = $this->addedTerms;
        $charges = $this->otherCharges;
        $user = $this->user;
        $date = $this->delivery_date;
        $referenceNo = $this->reference_no;
        $this->savedDeliveryNote->customer_id = $customer?->id;
        $this->savedDeliveryNote->delivery_date = $date;
        $this->savedDeliveryNote->reference_no = $referenceNo;
        $this->savedDeliveryNote->created_by = $this->user->id;
        $this->savedDeliveryNote->business_id = $this->user->business->id;
        $this->savedDeliveryNote->update();
        $this->savedDeliveryNote->deliveryProducts()->delete();
        foreach($products as $key => $product) {
            $deliveryProduct = new DeliveryProduct();
            $deliveryProduct->product_id = $product['product']['id'];
            $deliveryProduct->delivery_note_id = $this->savedDeliveryNote->id;
            $deliveryProduct->quantity = $product['quantity'];
            $deliveryProduct->description = $product['description'];
            $deliveryProduct->price = $product['price'];
            $deliveryProduct->sort_order = $key;
            $deliveryProduct->save();
        }

        if($terms) {
            $termIds = $terms->pluck('id')->toArray();
            $this->savedDeliveryNote->terms()->sync($termIds);
        }
        

        $this->dispatch('deliveryNoteUpdated');
    }

    public function generatePdf() {
        $this->validate([
            'delivery_date' => 'required',
            'addedCustomer' => 'required',
            'addedProducts' => 'required',
        ], [
            'delivery_date' => 'Please select delivery date',
            'addedCustomer' => 'Please add customer',
            'addedProducts' => 'Please add product'
        ]);

        $products = $this->addedProducts;
        $customer = $this->addedCustomer;
        $terms = $this->addedTerms;
        $charges = $this->otherCharges;
        $user = $this->user;
        $date = $this->delivery_date;
        $referenceNo = $this->reference_no;
        $orderNumber = $this->savedDeliveryNote->order_no;
        $fileName = 'order'.$orderNumber.'.pdf';
        $pdf = PDF::loadView('make-delivery-notes.pdf', compact('products', 'customer', 'terms', 'user', 'date', 'referenceNo', 'orderNumber'));
        return response()->streamDownload(function () use ($pdf) {
           echo  $pdf->stream();
        }, $fileName);
    }

    public function render()
    {
        return view('livewire.edit-delivery-notes');
    }
}

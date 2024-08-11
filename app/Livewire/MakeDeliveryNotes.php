<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CustomerModel;
use App\Models\DeliveryProduct;
use App\Models\MakeDeliveryNote;
use App\Models\TermsModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\On;

class MakeDeliveryNotes extends Component
{
    public $totalAmount = 0;
    public $delivery_date;
    public $reference_no = '';
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

    
    public function mount() {
        $this->user = auth()->user();
        $this->delivery_date = Carbon::now()->format('Y-m-d');
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
        $date = $this->delivery_date;
        $referenceNo = $this->reference_no;
        $lastDeliveryNote = MakeDeliveryNote::query()->orderBy('order_no', 'desc')->first();
        $deliveryNotes = new MakeDeliveryNote();
        $deliveryNotes->customer_id = $customer?->id;
        $deliveryNotes->created_by = $this->user->id;
        $deliveryNotes->business_id = $this->user->business->id;
        $deliveryNotes->order_no = !is_null($lastDeliveryNote?->order_no) ? ($lastDeliveryNote?->order_no +1) : 1;
        $deliveryNotes->delivery_date = $date;
        $deliveryNotes->reference_no = $referenceNo;
        $deliveryNotes->save();
        $orderNumber = $deliveryNotes->order_no;

        foreach($products as $key => $product) {
            $deliveryProduct = new DeliveryProduct();
            $deliveryProduct->product_id = $product['product']['id'];
            $deliveryProduct->delivery_note_id = $deliveryNotes->id;
            $deliveryProduct->quantity = $product['quantity'];
            $deliveryProduct->description = $product['description'];
            $deliveryProduct->price = $product['price'];
            $deliveryProduct->sort_order = $key;
            $deliveryProduct->save();
        }

        $termIds = array_keys($terms);
        $deliveryNotes->terms()->sync($termIds);

        $route = route('make-delivery-note.edit', $deliveryNotes->uuid);
        $this->dispatch('deliveryNoteCreated', $route);
    }

    public function render()
    {
        return view('livewire.make-delivery-notes');
    }
}

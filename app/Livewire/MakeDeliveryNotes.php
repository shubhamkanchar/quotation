<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CustomerModel;
use App\Models\TermsModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\On;

class MakeDeliveryNotes extends Component
{
    public $totalAmount = 0;
    public $delivery_date;
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
        $totalAmount = $this->totalAmount;
        $pdf = PDF::loadView('make-delivery-notes\pdf', compact('products', 'customer', 'terms', 'user', 'date'));
        return response()->streamDownload(function () use ($pdf) {
           echo  $pdf->stream();
        }, 'order.pdf');
    }

    public function render()
    {
        return view('livewire.make-delivery-notes');
    }
}

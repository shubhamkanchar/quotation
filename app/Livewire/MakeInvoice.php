<?php

namespace App\Livewire;

use App\Models\CustomerModel;
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
        $this->invoice_date = Carbon::now()->format('Y-m-d');
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
        $date = $this->invoice_date;
        $totalAmount = $this->totalAmount;
        $paidAmount = $this->paidAmount;
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en'); // 'en' for English
        $amountInWord = $numberTransformer->toWords($this->totalAmount);
        $pdf = Pdf::loadView('make-invoice\pdf', compact('products', 'customer', 'terms', 'charges', 'user', 'date', 'totalAmount', 'amountInWord', 'paidAmount'));
        return response()->streamDownload(function () use ($pdf) {
           echo  $pdf->stream();
        }, 'invoice.pdf');
    }
    
    public function render()
    {
        return view('livewire.make-invoice');
    }
}

<?php

namespace App\Livewire;

use App\Models\MakeDeliveryNote;
use App\Models\MakeInvoice;
use App\Models\MakeProformaInvoice;
use App\Models\MakeQuotation;
use App\Models\TermsModel;
use Livewire\Component;

class TermsList extends Component
{
    public $terms;
    public $user;
    public $selectedTerms = [];
    public $is_all_selected;
    public $termName= '';
    public $componentName= '';

    public function mount($id=null) {
        $this->user = auth()->user();
        $this->terms = TermsModel::where('user_id', $this->user->id)
        ->where('type', $this->termName)
        ->get();

        $model = $this->getModel($id);
        if($model) {
            $selectedTerms = $model?->terms->pluck('id') ?? [];
            if($selectedTerms) {
                foreach($selectedTerms as $term) {
                    $this->selectedTerms[$term] = true;
                }
                if($selectedTerms->count() == $this->terms->count()) {
                    $this->is_all_selected = true;
                }
            }
        }
    
    }

    public function getModel($id) {
        $modelClass = match($this->componentName) {
            'Quotation' => MakeQuotation::class,
            'Delivery Notes' => MakeDeliveryNote::class,
            'Invoice' => MakeInvoice::class,
            'Proforma Invoice' => MakeProformaInvoice::class,
            'Purchase Order' => MakePurchaseOrder::class,
            default => null,
        };

        if ($modelClass) {
            return $modelClass::find($id);
        }

        return null;
    }
    public function updatedIsAllSelected($value) {
        foreach($this->terms as $term) {
            $this->selectedTerms[$term->id] = $value;
        } 
    }

    public function updatedSelectedTerms($value) {
        if(!$value) {
            $this->is_all_selected = $value;
        }
    }

    public function saveTerms() {
        $selectedTerms = array_filter($this->selectedTerms, function($value) {
            return $value;
        });
        $selectedTerms = array_keys($selectedTerms);
        $this->dispatch('termsAdded', $selectedTerms);
    }

    public function render()
    {
        return view('livewire.terms-list');
    }
}

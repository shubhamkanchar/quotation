<?php

namespace App\Livewire;

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

    public function mount(MakeQuotation $quotation=null) {
        $this->user = auth()->user();
        $this->terms = TermsModel::where('user_id', $this->user->id)
        ->where('type', $this->termName)
        ->get();

        $selectedTerms = $quotation?->terms->pluck('id') ?? [];
        if($selectedTerms) {
            foreach($selectedTerms as $term) {
                $this->selectedTerms[$term] = true;
            }
        }

        if($selectedTerms->count() == $this->terms->count()) {
            $this->is_all_selected = true;
        }
    
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

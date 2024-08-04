<?php

namespace App\Livewire;

use App\Models\MakeQuotation;
use App\Models\OtherCharge;
use Livewire\Component;

class OtherCharges extends Component
{
    public $other_charge_label = '';
    public $other_charge_amount;
    public $is_taxable = false;
    public $gst_percentage;

    public function saveCharges() {
        $rules = [
            'other_charge_label' => 'required',
            'other_charge_amount' => 'required',
            'gst_percentage' => $this->is_taxable ? 'required' : 'nullable',
        ];
        $this->validate($rules);
        $this->dispatch('otherChargesAdded', ['other_charge_label' => $this->other_charge_label, 'other_charge_amount' => $this->other_charge_amount, 'gst_percentage' => $this->gst_percentage,'is_taxable' => $this->is_taxable ]);
    }

    public function mount(OtherCharge $otherCharge=null) {
        if($otherCharge) {
            $this->other_charge_label = $otherCharge->label; 
            $this->other_charge_amount = $otherCharge->amount;
            $this->gst_percentage = $otherCharge->gst_percentage;
            $this->is_taxable = $otherCharge->is_taxable ? true : false;
        }
    }
    
    public function updatedIsTaxable($value) {
        if(!$value) {
            $this->reset('gst_percentage');
        }
    }
    public function render()
    {
        return view('livewire.other-charges');
    }
}

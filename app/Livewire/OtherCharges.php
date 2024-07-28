<?php

namespace App\Livewire;

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
    public function render()
    {
        return view('livewire.other-charges');
    }
}

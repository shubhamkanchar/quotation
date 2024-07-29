<?php

namespace App\Livewire;

use Livewire\Component;

class PaidInfo extends Component
{
    public $paid_date;
    public $paid_amount;
    public $notes;

    public $paidInfos = [];
    public $showInfo = true;

    public function addInfo() {

        $this->validate([
            'paid_date' => 'required',
            'paid_amount' => 'required',
        ]);
        $index = count($this->paidInfos);
        $this->paidInfos[$index]['date'] = $this->paid_date;
        $this->paidInfos[$index]['amount'] = $this->paid_amount;
        $this->paidInfos[$index]['notes'] = $this->notes;
        $this->showInfo = false;
        $this->reset('paid_date','paid_amount', 'notes');
        $this->dispatch('PaidInfoAdded', $this->paidInfos);
    }
    public function removeInfo($index) {
        unset($this->paidInfos[$index]);
        $this->paidInfos = array_values($this->paidInfos);
        $this->dispatch('PaidInfoRemoved', $this->paidInfos);
    }
    public function showForm() {
        $this->showInfo = true;
    }

    public function resetForm() {
        $this->showInfo = true;
        $this->reset('paid_date','paid_amount', 'notes');
    }

    public function render()
    {
        return view('livewire.paid-info');
    }
}

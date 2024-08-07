<?php

namespace App\Livewire;

use App\Models\PaidInfo as ModelsPaidInfo;
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

    public function mount($paidInfoIds = []) {
        $paidInfos = ModelsPaidInfo::whereIn('id', $paidInfoIds)->get();
        $index = 0;
        foreach($paidInfos as $info) {
            $this->paidInfos[$index]['date'] = $info->paid_date;
            $this->paidInfos[$index]['amount'] = $info->amount;
            $this->paidInfos[$index]['notes'] = $info->notes;
            $index++;
            $this->showInfo = false;
        }
        $this->dispatch('PaidInfoRemoved', $this->paidInfos);
    }

    public function render()
    {
        return view('livewire.paid-info');
    }
}

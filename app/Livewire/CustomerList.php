<?php

namespace App\Livewire;

use App\Models\CustomerModel;
use Livewire\Component;

class CustomerList extends Component
{
    public $customers;
    public $user;
    public $customerSearch;


    public function mount() {
        $this->user = auth()->user();
        $this->customers = CustomerModel::where('user_id', $this->user->id)->limit(10)->get();
    }

    public function updatedCustomerSearch($value) 
    {
        $query = CustomerModel::where('user_id', $this->user->id);
        if(!empty($value)) {
            $query->where(function($q) use($value) {
                $q->where('name', 'LIKE', '%' . $value . '%')
                  ->orWhere('company_name', 'LIKE', '%' . $value . '%');
            });
        }
        $this->customers = $query->get();

    }

    public function selectCustomer($id) {
        $this->dispatch('customerAdded', $id);
    }
    
    public function resetSearch() {
        $this->customers = CustomerModel::where('user_id', $this->user->id)->limit(10)->get();
        $this->reset('customerSearch');
    }
    
    public function render()
    {
        return view('livewire.customer-list');
    }
}

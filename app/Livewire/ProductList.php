<?php

namespace App\Livewire;

use App\Models\ProductModel;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProductList extends Component
{
    public $products;
    public $user;
    public $productSearch;
    public $selectedProduct;
    #[Validate('required')] 
    public $quantity;
    #[Validate('required')] 
    public $price;
    public $description;
    public $componentName = '';

    public function mount() {
        $this->user = auth()->user();
        $this->products = ProductModel::where('user_id', $this->user->id)->limit(10)->get();
    }

    public function selectProduct($id) {
        $this->selectedProduct = ProductModel::where('user_id', $this->user->id)
        ->where('id', $id)->first();
        $this->quantity = 1;
        $this->price = $this->selectedProduct->price;
        $this->description = $this->selectedProduct->description;
    }

    public function resetProductList() {
        $this->reset('selectedProduct', 'productSearch', 'quantity', 'description');
        $this->products = ProductModel::where('user_id', $this->user->id)->limit(10)->get();
    }

    public function updatedProductSearch($value) 
    {
        $query = ProductModel::where('user_id', $this->user->id);
        if(!empty($value)) {
            $query->where(function($q) use($value) {
                $q->where('product_name', 'LIKE', '%' . $value . '%');
            });
        }
        $this->products = $query->get();

    }

    public function addToQuotation() {
        $this->validate();
        $this->dispatch('productAdded', ['product' => $this->selectedProduct, 'quantity' => $this->quantity, 'price' => $this->price,'description' => $this->description ]);
    }

    public function addProduct() {

    }

    public function render()
    {
        return view('livewire.product-list');
    }
}

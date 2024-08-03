<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationProducts extends Model
{
    use HasFactory;

    public function product() {
        return $this->belongsTo(ProductModel::class, 'product_id', 'id');
    }
}

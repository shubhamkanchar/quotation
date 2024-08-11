<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MakePurchaseOrder extends Model
{
    use HasFactory;

    public function otherCharge()
    {
        return $this->morphOne(OtherCharge::class, 'chargeable');
    }
    public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id', 'id');
    }

    public function purchaseOrderProducts() {
        return $this->hasMany(PurchaseOrderProduct::class, 'purchase_order_id', 'id');
    }

    public function terms()
    {
        return $this->belongsToMany(TermsModel::class, 'purchase_terms', 'purchase_order_id', 'term_id');
    }

    protected static function boot()
    {
        parent::boot();

        // Automatically generate a UUID for the quotation
        static::creating(function ($quotation) {
            $quotation->uuid = (string) Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}

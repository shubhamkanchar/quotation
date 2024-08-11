<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class MakeDeliveryNote extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id', 'id');
    }

    public function deliveryProducts() {
        return $this->hasMany(DeliveryProduct::class, 'delivery_note_id', 'id');
    }

    public function terms()
    {
        return $this->belongsToMany(TermsModel::class, 'delivery_terms', 'delivery_note_id', 'term_id');
    }

    public function otherInfos()
    {
        return $this->morphMany(OtherInfo::class, 'info');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quotation) {
            $quotation->uuid = (string) Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}

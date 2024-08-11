<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MakeQuotation extends Model
{
    use HasFactory;

    public function otherCharge()
    {
        return $this->morphOne(OtherCharge::class, 'chargeable');
    }
    public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id', 'id')->withTrashed();
    }

    public function quotationProducts() {
        return $this->hasMany(QuotationProducts::class, 'quotation_id', 'id');
    }

    public function terms()
    {
        return $this->belongsToMany(TermsModel::class, 'quotation_terms', 'quotation_id', 'term_id');
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

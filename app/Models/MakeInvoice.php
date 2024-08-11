<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class MakeInvoice extends Model
{
    use HasFactory;

    public function otherCharge()
    {
        return $this->morphOne(OtherCharge::class, 'chargeable');
    }

    public function paidInfos()
    {
        return $this->morphMany(PaidInfo::class, 'info');
    }

    public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id', 'id');
    }

    public function invoiceProducts() {
        return $this->hasMany(InvoiceProduct::class, 'invoice_id', 'id');
    }

    public function terms()
    {
        return $this->belongsToMany(TermsModel::class, 'invoice_terms', 'invoice_id', 'term_id');
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

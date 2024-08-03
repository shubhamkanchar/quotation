<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherCharge extends Model
{
    protected $fillable = [
        'label',
        'amount',
        'gst_percentage',
        'gst_amount',
        'chargeable_id',
        'chargeable_type',
    ];

    use HasFactory;

    public function chargeable()
    {
        return $this->morphTo();
    }
}

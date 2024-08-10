<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsModel extends Model
{
    use HasFactory;

    public function quotations()
    {
        return $this->belongsToMany(MakeQuotation::class, 'quotation_terms', 'term_id', 'quotation_id');
    }
    
    public function deliveryNotes()
    {
        return $this->belongsToMany(MakeDeliveryNote::class, 'delivery_terms', 'term_id', 'delivery_note_id');
    }
}

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
}

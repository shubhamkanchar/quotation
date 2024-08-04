<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherInfo extends Model
{
    use HasFactory;

    public function info()
    {
        return $this->morphTo();
    }
}

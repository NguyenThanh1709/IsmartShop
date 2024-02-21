<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_commune extends Model
{
    use HasFactory;
    function district()
    {
        return $this->belongsTo('App\Models\tbl_district');
    }
}

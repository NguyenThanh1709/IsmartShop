<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_district extends Model
{
    use HasFactory;
    function city(){
        return $this->belongsTo('App\Models\tbl_province_city');
    }
}

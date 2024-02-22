<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    // use SoftDeletes;
    protected $fillable = ['id', 'name', 'email', 'phone', 'total', 'status', 'province-city', 'district', 'commune', 'address', 'payment_method', 'note', 'matp', 'maqh', 'xaid'];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    function city()
    {
        return $this->belongsTo('App\Models\Tbl_province_city.php');
    }
    function district()
    {
        return $this->belongsTo('App\Models\Tbl_district');
    }
    function commune()
    {
        return $this->belongsTo('App\Models\Tbl_commune');
    }
}

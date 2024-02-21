<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['product_id', 'color_id', 'config_id', 'quantity', 'price', 'sale_off', 'discount', 'represent'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function color(){
        return $this->belongsTo('App\Models\Color');
    }

    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function Config()
    {
        return $this->belongsTo('App\Models\Config');
    }

    public function Product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    // public function ProductWarehouse(){
    //     return $this->hasOne(Product::class);
    // }
}

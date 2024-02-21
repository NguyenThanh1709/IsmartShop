<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable =['name', 'order_id','thumbnail','name', 'price', 'quantity', 'product_id', 'warehouse_id'];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function Product()
    {
        return $this->belongsTo('App\Models\Product');
    }
    public function Warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse');
    }
}

<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name', 'status', 'slug', 'price', 'brand_id', 'discount', 'user_id', 'product_cat_id', 'sale_off', 'desc', 'content', 'images', 'thumbnail'];

    public function setimagesAttribute($value)
    {
        $this->attributes['images'] = json_encode($value);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function productWareHourse()
    {
        return $this->hasMany('App\Models\Warehouse');
    }

    public function Brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }

    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function productCat()
    {
        return $this->belongsTo('App\Models\ProductCat');
    }

    public function color(){
        return $this->belongsTo('App\Models\Color');
    }

    public function Config()
    {
        return $this->belongsTo('App\Models\Config');
    }
}

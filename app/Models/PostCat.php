<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCat extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id', 'parent_id', 'status', 'slug'];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    function post(){
        return $this->hasOne('App\Models\Post');
    }

    function user(){
        return $this->belongsTo('App\Models\User');
    }
}

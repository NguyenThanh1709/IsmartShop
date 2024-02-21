<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name', 'title', 'content', 'user_id', 'status'];
    function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}

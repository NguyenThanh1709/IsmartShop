<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = ['name', 'user_id', 'thumbnail', 'status', 'content', 'slug', 'link'];

    function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}

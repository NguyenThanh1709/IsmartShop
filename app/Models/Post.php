<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['title', 'user_id', 'post_cat_id', 'status', 'slug', 'content', 'thumbnail'];

    function PostCat()
    {
        return $this->belongsTo('App\Models\PostCat');
    }

    function user(){
        return $this->belongsTo('App\Models\User');
    }
}

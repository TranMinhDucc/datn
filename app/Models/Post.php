<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'category_id', 'title', 'thumbnail', 'slug', 'content', 'status', 'view'
    ];

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }
}
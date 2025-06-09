<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $fillable = ['title', 'slug', 'name', 'icon', 'status'];

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
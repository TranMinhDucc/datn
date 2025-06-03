<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HasFactory;
class Product extends Model
{
    const CREATED_AT = 'create_gettime';
    const UPDATED_AT = 'update_gettime';

    protected $fillable = [
        'user_id', 'name', 'slug', 'images', 'description', 'note', 'code',
        'price', 'cost', 'discount', 'min', 'max', 'sold',
        'category_id', 'status', 'check_live', 'text_txt'
    ];
}

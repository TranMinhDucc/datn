<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Product extends Model
{
  
protected $fillable = [
    'user_id', 'name', 'slug', 'images', 'description', 'note', 'code',
    'price', 'cost', 'discount','min_purchase_quantity', 'max_purchase_quantity', 'sold',
    //  'quantity',
    'category_id', 'status', 'check_live', 'text_txt','short_desc',
];
 public function category()
    {
        return $this->belongsTo(Category::class);
    }

}

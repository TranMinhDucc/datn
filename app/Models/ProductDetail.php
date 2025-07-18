<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{

    protected $fillable = ['product_id', 'group_name', 'label', 'value'];
    protected $table = 'product_details'; // đảm bảo đúng tên
    protected $guarded = [];
    protected $primaryKey = 'id';
    public $timestamps = true;
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

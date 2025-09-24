<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'image',
        'name',
        'parent_id',
        'description',

    ];

    // Quan hệ với danh mục cha
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Quan hệ với danh mục con
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public static function getAllChildIds($id)
    {
        $children = self::where('parent_id', $id)->get();
        $ids = [];

        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, self::getAllChildIds($child->id)); // đệ quy
        }

        return $ids;
    }
    // Quan hệ với sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function childrenRecursive()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('childrenRecursive');
    } 
}

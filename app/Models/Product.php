<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'photo',
        'price',
        'qty',
        'description',
    ];

    // many to one
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}

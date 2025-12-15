<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'stock_quantity',
        'sku',
        'image',
        'images',
        'is_featured',
        'is_active',
        'views_count',
        'rating',
        'reviews_count',
        'weight',
        'calories',
        'protein',
        'carbs',
        'fat',
        'fiber',
        'ingredients',
        'allergens',
    ];

    protected $casts = [
        'images' => 'array',
        'ingredients' => 'array',
        'allergens' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->sale_price) return 0;
        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }
}

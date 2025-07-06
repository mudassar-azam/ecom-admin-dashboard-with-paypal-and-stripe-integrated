<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'brand_id',
        'category_id',
        'slug',
        'description',
        'short_description',
        'sku',
        'price',
        'sale_price',
        'currency',
        'quantity',
        'stock_status',
        'main_image',
        'gallery_images',
        'video_url',
        'weight',
        'dimensions',
        'tags',
        'features',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_canonical',
        'cost_price',
        'shipping_class',
        'is_featured',
        'status',
        'average_rating',
        'published_at'
    ];

    protected $casts = [
        'gallery_images' => 'array'
    ];

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

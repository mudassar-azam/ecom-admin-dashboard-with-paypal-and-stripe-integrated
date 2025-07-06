<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    protected $fillable = [
        'product_id',
        'attribute_id',
        'attribute_value_id',
        'name',
        'sku',
        'description',
        'sale_price',
        'stock_status',
        'gallery_images'
    ];

    protected $casts = [
        'gallery_images' => 'array'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function value()
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}

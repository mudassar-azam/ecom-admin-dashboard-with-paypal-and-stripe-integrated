<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'shipping_address',
        'city',
        'state',
        'zipcode',
        'country',
        'total',
        'payment_method',
        'paypal_order_id',
        '_order_id',
        'status',
        'status_notes',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

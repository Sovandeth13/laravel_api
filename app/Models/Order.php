<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_id',
    ];

    // Relation to order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relation to user (if needed)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

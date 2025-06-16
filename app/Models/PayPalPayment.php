<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayPalPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'paypal_transaction_id', 'status', 'amount',
    ];
}

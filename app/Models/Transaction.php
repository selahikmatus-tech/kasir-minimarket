<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number',
        'total_amount',
        'tax',
        'discount',
        'final_amount',
        'item_count',
        'user_id',
        'customer_name',
        'payment_amount',
        'change_amount',
        'transaction_date',
        'payment_method',
        'payment_code'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'transaction_date' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}

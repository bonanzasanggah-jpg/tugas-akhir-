<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transactions'; 

    protected $fillable = [
        'invoice_number', 
        'product_id',
        'user_id', 
        'quantity',
        'total_price',
        'paid_amount',
        'change_amount',
        'payment_method',
        'status'
    ];

    public function getRouteKeyName()
    {
        return 'invoice_number';
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
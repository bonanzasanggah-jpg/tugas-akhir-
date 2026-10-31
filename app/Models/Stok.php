<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stock_logs'; 
    protected $fillable = ['product_id', 'user_id', 'type', 'quantity', 'description'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}
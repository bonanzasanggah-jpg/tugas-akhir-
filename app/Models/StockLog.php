<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $table = 'stock_logs'; 

    protected $fillable = ['product_id', 'user_id', 'type', 'quantity', 'description','note'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
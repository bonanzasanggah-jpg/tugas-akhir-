<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    'category_id',
    'supplier_id',
    'sku',
    'barcode', 
    'name',
    'buy_price',
    'sell_price',
    'stock',
    'min_stock',
    'expired_date',
    'expired_status'
];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }
}
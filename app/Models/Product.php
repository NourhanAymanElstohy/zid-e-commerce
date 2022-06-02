<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'price',
        'quantity',
        'shipping_cost',
        'is_vat_included',
        'vat_percentage',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }
}

<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{

    public function create($request)
    {
        return Product::create([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'price' => $request->price,
            'shipping_cost' => $request->shipping_cost,
            'is_vat_included' => $request->is_vat_included,
            'vat_percentage' => $request->vat_percentage,
            'store_id' => $request->store_id,
            'quantity' => $request->quantity ?: 0,
        ]);
    }

    public function update(Product $product, $request)
    {
        return $product->update([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'price' => $request->price,
            'shipping_cost' => $request->shipping_cost,
            'is_vat_included' => $request->is_vat_included,
            'vat_percentage' => $request->vat_percentage,
            'store_id' => $request->store_id,
            'quantity' => $request->quantity ?: 0,
        ]);
    }
}

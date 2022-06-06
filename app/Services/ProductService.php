<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductService
{

    public function getProducts()
    {
        return Product::with('store')->paginate();
    }

    public function getProductData($productId): Product
    {
        $product = Product::find($productId);
        return $product ? $product : null;
    }

    public function create($request): Product
    {
        return Product::create([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'price' => $request->price,
            'shipping_cost' => $request->shipping_cost,
            'is_vat_included' => $request->is_vat_included,
            'vat_percentage' => $request->is_vat_included == 0 ? $request->vat_percentage : 0,
            'store_id' => $request->store_id,
            'quantity' => $request->quantity ?: 0,
        ]);
    }

    public function update(Product $product, $request): bool
    {
        $product->update([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'price' => $request->price,
            'shipping_cost' => $request->shipping_cost,
            'is_vat_included' => $request->is_vat_included,
            'vat_percentage' => $request->is_vat_included == 0 ? $request->vat_percentage : 0,
            'store_id' => $request->store_id,
            'quantity' => $request->quantity ?: 0,
        ]);

        return true;
    }

    public function delete($productId)
    {
        $user = Auth::user();
        $product = Product::find($productId);
        if ($product) {
            if ($user->stores->contains($product->store_id)) {
                $product->delete();
                return 1;
            } else
                return 2;
        } else
            return 0;
    }
}

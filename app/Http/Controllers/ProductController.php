<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function getProducts(Request $request)
    {
        $products = Product::where('store_id', $request->id)->with('store')->get();
        if ($products->count() > 0)
            return response()->json(["data" => $products], 200);
        else
            return response()->json(['message' => 'No Products Found'], 404);
    }

    public function getProduct(Request $request)
    {
        $product = Product::find($request->id);
        if ($product)
            return response()->json(["data" => $product], 200);
        else
            return response()->json(['message' => 'No Products Found', 404]);
    }

    public function addProduct(ProductRequest $request)
    {
        $user = Auth::user();
        $user_stores_ids = $user->stores->pluck('id');

        if ($user_stores_ids->contains($request->store_id)) {
            $product = Product::create([
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
            return response()->json(['message' => 'Product has been added successfully', "data" => $product], 200);
        } else {
            return response()->json(["message" => "Unauthorized"], 401);
        }
    }

    public function updateProduct(ProductRequest $request)
    {
        $product = Product::find($request->id);
        if ($product) {
            $user = Auth::user();
            $user_stores_ids = $user->stores->pluck('id');

            if ($user_stores_ids->contains($request->store_id)) {
                $product->update([
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

                return response()->json(['message' => 'Product has been updated successfully', ["data" => $product]], 200);
            } else
                return response()->json(["message" => "Unauthorized"], 401);
        } else
            return response()->json(['message' => 'No Products Found'], 404);
    }

    public function deleteProduct(Request $request)
    {
        $user = Auth::user();
        $product = Product::find($request->id);
        if ($product) {
            if ($user->stores->contains($product->store_id)) {
                $product->delete();
                return response()->json(['message' => 'Product has been deleted successfully'], 200);
            } else
                return response()->json(['message' => 'Unauthorized'], 401);
        } else
            return response()->json(['message' => 'No Product Found'], 404);
    }
}

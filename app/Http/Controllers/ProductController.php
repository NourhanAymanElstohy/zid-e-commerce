<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getProducts()
    {
        $products = Product::with('store')->paginate();
        return response()->json(["data" => $products], 200);
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
            $product = $this->productService->create($request);
            return response()->json(['message' => 'Product has been added successfully', "data" => $product], 200);
        } else {
            return response()->json(["message" => "Unauthorized to access this store"], 401);
        }
    }

    public function updateProduct(Request $request)
    {
        $product = Product::find($request->id);
        if ($product) {
            $user = Auth::user();
            $user_stores_ids = $user->stores->pluck('id');

            if ($user_stores_ids->contains($request->store_id)) {
                $this->productService->update($product, $request);
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

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

    public function index()
    {
        $products = $this->productService->getProducts();
        return response()->json(["data" => $products], 200);
    }

    public function show(Request $request)
    {
        $product = $this->productService->getProductData($request->id);
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

    public function updateProduct(ProductRequest $request)
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
        $result = $this->productService->delete($request->id);
        if ($result == 1)
            return response()->json(['message' => 'Product has been deleted successfully'], 200);
        elseif ($result == 2)
            return response()->json(['message' => 'Unauthorized'], 401);
        elseif ($result == 0)
            return response()->json(['message' => 'No Product Found'], 404);
    }
}

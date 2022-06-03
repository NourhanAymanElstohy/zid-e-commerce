<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function cartList()
    {
        $user = Auth::user();
        $cart = $user->cart;
        if ($cart)
            return response()->json(["data" => $cart->with('products')->get()], 200);
        else
            return response()->json(["message" => "No Carts Found"], 404);
    }

    public function addToCart(CartRequest $request)
    {
        $user = Auth::user();
        $cart = $user->cart;
        $product = Product::find($request->product_id);

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $user->id,
            ]);
        }

        if ($product->quantity > 0 && !$cart->products->contains($product->id)) {

            $cart->products()->attach($product->id, ['quantity' => 1]);
            $product->quantity -= 1;
            $product->save();
            return response()->json(["message" => "Product has been added to cart", "data" => $cart->with('products')->get()], 200);
        } elseif ($product->quantity > 0 && $cart->products->contains($product->id)) {

            $quantity = $cart->products()->where('product_id', $product->id)->first()->pivot->quantity;
            $cart->products()->updateExistingPivot($product->id, ['quantity' => $quantity += 1]);
            $product->quantity -= 1;
            $product->save();
            return response()->json(["message" => "Product has been added to cart", "data" => $cart->with('products')->get()], 200);
        } elseif ($product->quantity == 0) {
            return response()->json(["message" => "Product is out of stock"], 404);
        }
    }

    public function removeProductFromCart(CartRequest $request)
    {
        $user = Auth::user();
        $cart = $user->cart;
        $cart_products = $cart->products->pluck('id');
        $product = Product::find($request->product_id);

        if ($cart_products->count() > 0 && $cart_products->contains($product->id)) {
            $quantity = $cart->products()->where('product_id', $product->id)->first()->pivot->quantity;

            if ($quantity == 1) {

                $cart->products()->detach($product->id);
                $product->quantity += 1;
                $product->save();
                return response()->json(["message" => "Product has been removed successfully", "data" => $cart->with('products')->get()], 200);
            } elseif ($cart_products->contains($product->id) && $quantity > 1) {

                $cart->products()->updateExistingPivot($product->id, ['quantity' => $quantity -= 1]);
                $product->quantity += 1;
                $product->save();
                return response()->json(["message" => "Product has been removed successfully", "data" => $cart->with('products')->get()], 200);
            }
        } else
            return response()->json(["message" => "This Product is not in cart"], 404);
    }

    public function clearAllCart()
    {
        $user = Auth::user();
        $cart = $user->cart;
        $cart_products = $cart->products;

        if ($cart_products->count() > 0) {
            foreach ($cart_products as $product) {
                $quantity = $cart->products()->where('product_id', $product->id)->first()->pivot->quantity;
                $cart->products()->updateExistingPivot($product->id, ['quantity' => 0]);
                $product->quantity += $quantity;
                $product->save();
            }
            $cart->products()->detach($cart_products->pluck('id'));
            return response()->json(["message" => "Cart has been cleared successfully", "data" => $cart->with('products')->get()], 200);
        } else
            return response()->json(["message" => "Cart has no products"], 200);
    }

    public function getTotalCart()
    {
        $user = Auth::user();
        $cart_products = $user->cart->products;
        $total_price = 0;
        $total_vat = 0;
        $total_shipping_cost = 0;

        foreach ($cart_products as $product) {
            $total_price += $product->price;
            $total_shipping_cost += $product->shipping_cost ?: 0;
            if ($product->is_vat_included == 0) {
                $quantity = $product->pivot->quantity;
                $total_vat += ($product->price * ($product->vat_percentage / 100)) * $quantity;
            }
        }
        $totals = $total_price + $total_shipping_cost + $total_vat;
        return response()->json(["cart" => $user->cart->with('products')->get(), "total_cost" => $totals]);
    }
}

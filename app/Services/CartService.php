<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CartService
{

    public function listCartItems(User $user)
    {
        return $user->cart ? $user->cart->with('products')->get() : null;
    }

    public function addItems($request)
    {
        $user = Auth::user();
        $cart = $user->cart;
        $product = Product::find($request->product_id);

        if (!$cart) {
            $cart = $this->createCart($user);
        }

        if ($product->quantity > 0 && !$cart->products->contains($product->id)) {

            $cart->products()->attach($product->id, ['quantity' => 1]);
            $product->quantity -= 1;
            $product->save();
            return $cart->with('products')->get();
        } elseif ($product->quantity > 0 && $cart->products->contains($product->id)) {

            $quantity = $cart->products()->where('product_id', $product->id)->first()->pivot->quantity;
            $cart->products()->updateExistingPivot($product->id, ['quantity' => $quantity += 1]);
            $product->quantity -= 1;
            $product->save();
            return $cart->with('products')->get();
        } elseif ($product->quantity == 0) {
            return null;
        }
    }
    public function createCart($user)
    {
        return Cart::create([
            'user_id' => $user->id,
        ]);
    }

    public function removeItems($request)
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
                return $cart->with('products')->get();
            } elseif ($cart_products->contains($product->id) && $quantity > 1) {

                $cart->products()->updateExistingPivot($product->id, ['quantity' => $quantity -= 1]);
                $product->quantity += 1;
                $product->save();
                return $cart->with('products')->get();
            }
        } else
            return null;
    }

    public function clearCart()
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
            return $cart->with('products')->get();
        } else return null;
    }

    public function getTotalCart()
    {
        $user = Auth::user();
        $cart_products = $user->cart->products;
        $total_price = 0;
        $total_vat = 0;
        $total_shipping_cost = 0;

        if ($cart_products->count() > 0) {
            foreach ($cart_products as $product) {
                $total_price += $product->price;
                $total_shipping_cost += $product->shipping_cost ?: 0;
                if ($product->is_vat_included == 0) {
                    $quantity = $product->pivot->quantity;
                    $total_vat += ($product->price * ($product->vat_percentage / 100)) * $quantity;
                }
            }
            $totals = $total_price + $total_shipping_cost + $total_vat;
            return [
                "total_cost" => $totals,
                "cart" => $user->cart->with('products')->get(),
            ];
        } else return null;
    }
}

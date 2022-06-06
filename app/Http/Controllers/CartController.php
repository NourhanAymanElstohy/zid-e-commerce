<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function cartList()
    {
        $cartItems = $this->cartService->listCartItems(Auth::user());
        if ($cartItems)
            return response()->json(["data" => $cartItems], 200);
        else
            return response()->json(["message" => "No Carts Found"], 404);
    }

    public function addToCart(CartRequest $request)
    {
        $cart = $this->cartService->addItems($request);
        if ($cart)
            return response()->json(["message" => "Product has been added to cart", "data" => $cart], 200);
        else
            return response()->json(["message" => "Product is out of stock"], 404);
    }

    public function removeProductFromCart(CartRequest $request)
    {
        $cart = $this->cartService->removeItems($request);
        if ($cart)
            return response()->json(["message" => "Product has been removed successfully", "data" => $cart], 200);
        else
            return response()->json(["message" => "This Product is not in cart"], 404);
    }

    public function clearAllCart()
    {
        $cart = $this->cartService->clearCart();
        if ($cart)
            return response()->json(["message" => "Cart has been cleared successfully", "data" => $cart], 200);
        else
            return response()->json(["message" => "Cart has no products"], 200);
    }

    public function getTotalCart()
    {
        $result = $this->cartService->getTotalCart();
        if ($result)
            return response()->json($result);
        else
            return response()->json(["message" => "Cart has no products"]);
    }
}

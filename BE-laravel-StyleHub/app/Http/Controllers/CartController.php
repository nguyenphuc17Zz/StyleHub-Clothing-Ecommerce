<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    //------------------------API--------------------
    public function cart(Request $request)
    {
        $user = $request->user();
        $cart_items = $this->cartService->create($user->id);
        return response()->json([
            "success" => true,
            "cart_items" => $cart_items,
        ]);
    }
    public function updateAddItem(Request $request)
    {
        $user = $request->user();
        $user_id = $user->id;
        $cart_id = $this->cartService->findByUserId($user_id)->id;
        $this->cartService->updateOrAddItem($cart_id, $request['product_id'], $request['variant_id'], $request['quantity']);
        $cart_items = $this->cartService->create($user->id);

        return response()->json([
            'cart_items' => $cart_items,
            'success' => true,
        ]);
    }
    public function deleteItem(Request $request)
    {
        $user = $request->user();
        $user_id = $user->id;
        $cart_id = $this->cartService->findByUserId($user_id)->id;
        $this->cartService->deleteItem($cart_id, $request['product_id'], $request['variant_id']);
        $cart_items = $this->cartService->create($user->id);

        return response()->json([
            'cart_items' => $cart_items,
            'success' => true,
        ]);
    }
}

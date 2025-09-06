<?php

namespace App\Repositories\CartRepo;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Support\Collection;

class CartRepository implements ICartRepository
{


    public function create($user_id)
    {
        $existingCart = $this->findByUserId($user_id);
        if ($existingCart) {
            $cart_items = $this->getProductsInCartByCartId($existingCart->id);
            return $cart_items;
        }

        $cart = Cart::create(['user_id' => $user_id]);
        $cart_items = $this->getProductsInCartByCartId($cart->id);

        return $cart_items;
    }


    public function findByUserId(int $userId)
    {
        return Cart::where('user_id', $userId)->first();
    }

    public function getProductsInCartByCartId($cartId)
    {
        $cart = Cart::find($cartId);

        if (!$cart) {
            return [];
        }

        return $cart->items()
            ->with(['product', 'variant'])
            ->get()
            ->map(function ($item) {
                return [
                    'product'  => $item->product,
                    'variant'  => $item->variant,
                    'quantity' => $item->quantity,
                ];
            })
            ->toArray();
    }
    public function deleteItem(int $cartId, int $productId, int $variantId): bool
    {
        return CartItem::where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->delete() > 0;
    }
    public function updateOrAddItem(int $cartId, int $productId, int $variantId, int $quantity)
    {
        $item = CartItem::where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if ($item) {
            $item->quantity += $quantity;
            if ($item->quantity <= 0) {
                $this->deleteItem($cartId, $productId, $variantId);
                return;
            }
            $item->save();
        } else {
            $item = CartItem::create([
                'cart_id'    => $cartId,
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity'   => $quantity,
            ]);
        }

        return $item;
    }
    public function deleteAll($cart_id)
    {
        return CartItem::where('cart_id', $cart_id)->delete();
    }
}

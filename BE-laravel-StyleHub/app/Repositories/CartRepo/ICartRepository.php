<?php

namespace App\Repositories\CartRepo;

use Illuminate\Support\Collection;

interface ICartRepository
{

    public function create($user_id);

    public function findByUserId(int $userId);

    public function getProductsInCartByCartId($cartId);
    public function deleteItem(int $cartId, int $productId, int $variantId): bool;
    public function updateOrAddItem(int $cartId, int $productId, int $variantId, int $quantity);

    public function deleteAll($cart_id);
}

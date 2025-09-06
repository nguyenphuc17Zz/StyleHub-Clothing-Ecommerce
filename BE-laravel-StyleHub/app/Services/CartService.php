<?php

namespace App\Services;

use App\Repositories\CartRepo\ICartRepository;

class CartService
{
    protected $cartRepo;
    public function __construct(ICartRepository $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }
    public function create($user_id)
    {
        return $this->cartRepo->create($user_id);
    }

    public function deleteItem(int $cartId, int $productId, int $variantId)
    {
        return $this->cartRepo->deleteItem($cartId, $productId, $variantId);
    }
    public function updateOrAddItem(int $cartId, int $productId, int $variantId, int $quantity)
    {
        return $this->cartRepo->updateOrAddItem($cartId, $productId, $variantId, $quantity);
    }
    public function findByUserId($userId)
    {
        return $this->cartRepo->findByUserId($userId);
    }
    public function deleteAll($cart_id)
    {
        return $this->cartRepo->deleteAll($cart_id);
    }
}

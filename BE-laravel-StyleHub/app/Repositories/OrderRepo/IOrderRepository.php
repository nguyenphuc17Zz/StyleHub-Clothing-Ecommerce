<?php

namespace App\Repositories\OrderRepo;

interface IOrderRepository
{
    public function create($data);
    public function createOrder_items($data);
    public function update($id, $status);
    public function getAllOrderByUserId($userId);
    public function getOrder_Item($order_id);
}

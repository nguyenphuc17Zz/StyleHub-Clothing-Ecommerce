<?php

namespace App\Repositories\OrderRepo;

use App\Models\Order;
use App\Models\OrderItem;

class OrderRepository implements IOrderRepository
{
    public function create($data)
    {
        return Order::create($data);
    }
    public function createOrder_items($data)
    {
        foreach ($data as $item) {
            OrderItem::create($item);
        }
        return true;
    }
    public function update($id, $status)
    {
        $order = Order::find($id);
        $order->status = $status;
        $order->save();
        return $order;
    }
    public function getAllOrderByUserId($userId)
    {
        return Order::where("user_id", $userId)->get();
    }
    public function getOrder_Item($order_id)
    {
        return Order::with(['items.product', 'items.variant'])
            ->find($order_id);
    }

    public function findAll($keyword = null)
    {
        $query = Order::with('user'); // load quan hệ user

        if (!empty($keyword)) {
            $query->whereHas('user', function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%");
            });
        }

        $perPage = 10;
        return $query->paginate($perPage);
    }
    public function show($id)
    {
        return Order::with(['user', 'items.product', 'items.variant'])
            ->findOrFail($id);
    }

    public function updateStatus($id, $newStatus)
    {
        $order = Order::with('items.variant')->findOrFail($id);
        $oldStatus = $order->status;

        if ($oldStatus === $newStatus) {
            return $order;
        }

        foreach ($order->items as $item) {
            $variant = $item->variant;

            if ($oldStatus === 'pending' && $newStatus === 'approved') {
                if ($variant->stock < $item->quantity) {
                    throw new \Exception("Sản phẩm {$variant->product->name} không đủ số lượng tồn");
                }
                $variant->decrement('stock', $item->quantity);
            }

            if ($oldStatus === 'approved' && $newStatus === 'rejected') {
                $variant->increment('stock', $item->quantity);
            }
        }

        $order->update(['status' => $newStatus]);

        return $order;
    }


    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order->items()->count() > 0) {
            return false;
        }
        $order->delete();
        return true;
    }
    public function getOrderById($id)
    {
        return Order::with('user')->find($id);
    }
}

<?php

namespace App\Services;

use App\Repositories\OrderRepo\OrderRepository;
use App\Repositories\UserRepo\UserRepository;
use Illuminate\Support\Facades\Mail;

class OrderService
{
    protected $orderRepo;
    protected $userRepo;
    public function __construct(OrderRepository $orderRepo, UserRepository $userRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->userRepo = $userRepo;
    }
    public function create($data)
    {
        return $this->orderRepo->create($data);
    }
    public function createOrder_items($data)
    {
        return $this->orderRepo->createOrder_items($data);
    }
    public function update($id, $status) {}
    public function getAllOrderByUserId($user_id)
    {
        return $this->orderRepo->getAllOrderByUserId($user_id);
    }
    public function getOrder_Item($order_id)
    {
        return $this->orderRepo->getOrder_Item($order_id);
    }


    public function findAll($keyword = null)
    {
        return $this->orderRepo->findAll($keyword);
    }
    public function show($id)
    {
        return $this->orderRepo->show($id);
    }

    public function updateStatus($id, $status)
    {
        $order = $this->orderRepo->getOrderById($id);

        Mail::raw(
            "Đơn hàng mã #$id của bạn đã được cập nhật trạng thái: $status",
            function ($message) use ($order) {
                $message->to($order->user->email)
                    ->subject('Trạng thái đơn hàng');
            }
        );

        return $this->orderRepo->updateStatus($id, $status);
    }


    public function destroy($id)
    {
        return $this->orderRepo->destroy($id);
    }
}

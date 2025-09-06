<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    protected $orderService;
    protected $cartService;
    public function __construct(OrderService $orderService, CartService $cartService)
    {
        $this->orderService = $orderService;
        $this->cartService = $cartService;
    }
    public function add(Request $request)
    {
        $user = $request->user();

        $data_create_order = [
            "user_id" => $user->id,
            "total_amount" => $request->order['total_amount'],
            "status" => $request->order['status'],
        ];

        $order = $this->orderService->create($data_create_order);

        $items = $request->items;
        $itemsWithOrderId = [];

        foreach ($items as $item) {
            $itemsWithOrderId[] = [
                "order_id"   => $order->id,
                "product_id" => $item['product_id'],
                "variant_id" => $item['variant_id'],
                "quantity"   => $item['quantity'],
                "price"      => $item['price'],
            ];
        }
        $this->orderService->createOrder_items($itemsWithOrderId);

        $cart = $this->cartService->findByUserId($user->id);
        if ($cart) {
            $this->cartService->deleteAll($cart->id);
        }

        return response()->json([
            "success" => true,
            "order_id" => $order->id,
        ]);
    }
    public function getAllOrderByUserId(Request $request)
    {
        $user = $request->user();
        $user_id = $user->id;
        $orders = $this->orderService->getAllOrderByUserId($user_id);
        return response()->json([
            "success" => true,
            "orders" => $orders,
        ]);
    }
    public function getOrder_Items(Request $request, $id)
    {
        return ([
            "success" => true,
            "items" => $this->orderService->getOrder_Item($id),
        ]);
    }



    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $orders = $this->orderService->findAll($keyword);

        return view("orders.index", compact("orders", "keyword"));
    }

    public function show($id)
    {
        $order = $this->orderService->show($id);

        return view("orders.show", compact("order"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);
        $status = $request->input('status');

        $this->orderService->updateStatus($id, $status);

        return redirect()
            ->route('orders.index')
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    public function destroy($id)
    {
        $deleted = $this->orderService->destroy($id);

        if (!$deleted) {
            return redirect()
                ->route('orders.index')
                ->with('error', 'Không thể xóa đơn hàng vì vẫn còn sản phẩm!');
        }

        return redirect()
            ->route('orders.index')
            ->with('success', 'Xóa đơn hàng thành công!');
    }
}

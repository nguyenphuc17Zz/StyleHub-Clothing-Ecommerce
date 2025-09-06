<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

class DashboardService
{
    public function index($data)
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where("status", "approved")->sum("total_amount");
        $totalUsers = User::where("role", "user")->count();
        $pendingOrders = Order::where("status", "pending")->count();
        $approvedOrders = Order::where("status", "approved")->count();
        $rejectedOrders = Order::where("status", "rejected")->count();

        $topProducts = OrderItem::with(["product", "order"])
            ->whereHas("order", function ($q) {
                $q->where("status", "approved"); // chỉ lấy sản phẩm đã được duyệt
            })
            ->selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy("product_id")
            ->orderByDesc("total_sold")
            ->take(5)
            ->get();


        $from = $data["from"] ?? null;
        $to   = $data["to"] ?? null;

        if ($from && $to) {
            $revenueByRange = $this->revenueInRange($from, $to);
        } else {
            $startOfMonth = now()->startOfMonth();
            $endOfMonth   = now()->endOfMonth();

            $revenueByRange = $this->revenueInRange($startOfMonth, $endOfMonth);
        }
        return [
            "totalOrders" => $totalOrders,
            "totalRevenue" => $totalRevenue,
            "totalUsers" => $totalUsers,
            "pendingOrders" => $pendingOrders,
            "approvedOrders" => $approvedOrders,
            "rejectedOrders" => $rejectedOrders,
            "topProducts" => $topProducts,
            'revenueByRange' => $revenueByRange,
            'from' => $from,
            'to' => $to
        ];
    }
    public function revenueInRange($from, $to)
    {
        $fromDate = \Carbon\Carbon::parse($from)->startOfDay();
        $toDate   = \Carbon\Carbon::parse($to)->endOfDay();

        $diffDays = $fromDate->diffInDays($toDate);
        $interval = max(1, floor($diffDays / 5)); // số ngày mỗi mốc, tối thiểu 1

        $result = [];
        $currentStart = $fromDate->copy();

        for ($i = 1; $i <= 5; $i++) {
            $currentEnd = $currentStart->copy()->addDays($interval)->endOfDay();

            if ($i == 5) {
                $currentEnd = $toDate;
            }

            $revenue = \App\Models\Order::where('status', 'approved')
                ->whereBetween('created_at', [$currentStart, $currentEnd])
                ->sum('total_amount');

            $result[] = [
                'label' => $currentStart->format('d/m/Y') . ' - ' . $currentEnd->format('d/m/Y'),
                'revenue' => $revenue
            ];

            $currentStart = $currentEnd->copy()->addDay()->startOfDay();
        }

        return $result;
    }
}

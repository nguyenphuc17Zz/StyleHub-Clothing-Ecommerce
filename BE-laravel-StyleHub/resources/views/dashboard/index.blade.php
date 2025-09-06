@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

    <div class="container-fluid">
        <h2 class="mb-4">📊 Dashboard</h2>

        <!-- Form chọn khoảng ngày -->
        <form method="GET" action="{{ route('dashboard') }}" class="row g-3 mb-4 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Từ ngày</label>
                <input type="date" name="from" value="{{ $from }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Đến ngày</label>
                <input type="date" name="to" value="{{ $to }}" class="form-control">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Lọc dữ liệu</button>
            </div>
        </form>

        <!-- Thống kê nhanh -->
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card text-bg-primary shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="card-title">📦 Tổng đơn hàng</h6>
                        <p class="fs-3 mb-0">{{ $totalOrders }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-success shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="card-title">💰 Doanh thu</h6>
                        <p class="fs-3 mb-0">${{ number_format($totalRevenue, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-warning shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="card-title">👤 Người dùng</h6>
                        <p class="fs-3 mb-0">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-info shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="card-title">📈 Tỷ lệ Approved</h6>
                        <p class="fs-3 mb-0">
                            {{ $totalOrders > 0 ? round(($approvedOrders / $totalOrders) * 100, 1) : 0 }}%
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Đơn hàng theo trạng thái -->
        <div class="row g-3 mt-4">
            <div class="col-md-4">
                <div class="alert alert-warning text-center shadow-sm">
                    ⏳ Pending: <b>{{ $pendingOrders }}</b>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-success text-center shadow-sm">
                    ✅ Approved: <b>{{ $approvedOrders }}</b>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-danger text-center shadow-sm">
                    ❌ Rejected: <b>{{ $rejectedOrders }}</b>
                </div>
            </div>
        </div>

        <!-- Top sản phẩm -->
        <h4 class="mt-5">🔥 Top 5 sản phẩm bán chạy</h4>
        <table class="table table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Sản phẩm</th>
                    <th>Tổng bán</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topProducts as $index => $p)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $p->product->name ?? 'N/A' }}</td>
                        <td>{{ $p->total_sold }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Biểu đồ doanh thu -->
        <h4 class="mt-5">📊 Doanh thu trong khoảng {{ $from ?? now()->startOfMonth()->format('d/m/Y') }} -
            {{ $to ?? now()->endOfMonth()->format('d/m/Y') }}</h4>
        <div class="card shadow-sm p-3">
            <canvas id="revenueChart" height="120"></canvas>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json(collect($revenueByRange)->pluck('label')),
                datasets: [{
                    label: 'Doanh thu ($)',
                    data: @json(collect($revenueByRange)->pluck('revenue')),
                    borderWidth: 1,
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>

@endsection

@extends('layouts.app')
@section('title', 'Chi tiết đơn hàng')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="white-box">

                    <h3 class="box-title">Chi tiết đơn hàng #{{ $order->id }}</h3>

                    {{-- Thông tin chung --}}
                    <div class="mb-3">
                        <p><strong>Người đặt:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</p>
                        <p>
                            <strong>Trạng thái:</strong>
                            <span
                                class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'approved' ? 'success' : 'danger') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                        <p><strong>Tổng tiền:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                    </div>

                    {{-- Form cập nhật trạng thái --}}
                    <form action="{{ route('orders.update', $order->id) }}" method="POST"
                        class="mb-4 d-flex align-items-center">
                        @csrf
                        @method('PUT')
                        <label for="status" class="me-2"><strong>Cập nhật trạng thái:</strong></label>
                        <select name="status" id="status" class="form-select w-auto me-2">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $order->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </form>

                    {{-- Danh sách sản phẩm --}}
                    <h4 class="mt-4">Danh sách sản phẩm</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Sản phẩm</th>
                                    <th>Biến thể</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($order->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <img src="{{ asset('uploads/products/' . $item->product->thumbnail) }}"
                                                alt="{{ $item->product->name }}" width="50" class="me-2 rounded">
                                            {{ $item->product->name }}
                                        </td>
                                        <td>{{ $item->variant->size ?? 'N/A' }} - {{ $item->variant->color ?? 'N/A' }}
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Không có sản phẩm trong đơn hàng</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">⬅ Quay lại</a>

                        {{-- Nút xóa --}}
                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                            onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">🗑 Xóa đơn hàng</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

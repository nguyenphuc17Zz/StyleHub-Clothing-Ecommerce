@extends('layouts.app')
@section('title', 'Products')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="white-box">

                    <!-- Thanh search -->
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="input-group mb-3">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..."
                                value="{{ $keyword ?? '' }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-search"></i> Tìm
                            </button>
                        </div>
                    </form>

                    <h3 class="box-title">Danh sách sản phẩm
                        <a href="{{ route('products.create') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> Thêm mới
                        </a>
                    </h3>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Hình ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Giá</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $index => $product)
                                    <tr>
                                        <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                                        </td>
                                        <td>
                                            @if ($product->thumbnail)
                                                <img src="{{ asset('uploads/products/' . $product->thumbnail) }}"
                                                    alt="thumbnail" width="60" height="60"
                                                    style="object-fit: cover; border-radius: 5px;">
                                            @else
                                                <span class="text-muted">No image</span>
                                            @endif
                                        </td>
                                        <td class="txt-oflo">{{ $product->name }}</td>
                                        <td class="txt-oflo">{{ $product->category->name ?? 'N/A' }}</td>
                                        <td class="txt-oflo">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                        <td class="txt-oflo">{{ $product->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('products.edit', $product->id) }}"
                                                class="btn btn-sm btn-warning">Sửa</a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                style="display:inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Xóa sản phẩm này?')">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

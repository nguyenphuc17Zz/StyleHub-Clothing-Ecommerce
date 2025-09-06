@extends('layouts.app')
@section('title', 'Product Variants')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="white-box">

                    <!-- Thanh search -->
                    <form method="GET" action="{{ route('variants.index') }}">
                        <div class="input-group mb-3">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm variant..."
                                value="{{ $keyword ?? '' }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-search"></i> Tìm
                            </button>
                        </div>
                    </form>

                    <h3 class="box-title">Danh sách Product Variant
                        <a href="{{ route('variants.create') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> Thêm mới
                        </a>
                    </h3>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Sản phẩm</th>
                                    <th>Size</th>
                                    <th>Color</th>
                                    <th>Stock</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($variants as $index => $variant)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $variant->product->name ?? '-' }}</td>
                                        <td>{{ $variant->size ?? '-' }}</td>
                                        <td>{{ $variant->color }}</td>
                                        <td>{{ $variant->stock }}</td>
                                        <td>{{ $variant->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('variants.edit', $variant->id) }}"
                                                class="btn btn-sm btn-warning">Sửa</a>

                                            <form action="{{ route('variants.destroy', $variant->id) }}" method="POST"
                                                style="display:inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Xóa variant này?')">Xóa</button>
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
                        {{ $variants->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

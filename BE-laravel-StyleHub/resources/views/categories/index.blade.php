@extends('layouts.app')
@section('title', 'Categories')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="white-box">

                    <!-- Thanh search -->
                    <form method="GET" action="{{ route('categories.index') }}">
                        <div class="input-group mb-3">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm danh mục..."
                                value="{{ $keyword ?? '' }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-search"></i> Tìm
                            </button>
                        </div>
                    </form>

                    <h3 class="box-title">Danh sách danh mục
                        <a href="{{ route('categories.create') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> Thêm mới
                        </a>
                    </h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Tên danh mục</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $index => $category)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="txt-oflo">{{ $category->name }}</td>

                                        <td class="txt-oflo">{{ $category->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('categories.edit', $category->id) }}"
                                                class="btn btn-sm btn-warning">Sửa</a>

                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                                style="display:inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Xóa danh mục này?')">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $categories->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@extends('layouts.app')
@section('title', 'Thêm sản phẩm')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-lg-6 col-sm-12">
                <div class="white-box">
                    <h3 class="box-title">Thêm sản phẩm</h3>

                    <!-- Hiển thị lỗi validate -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên sản phẩm</label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="Nhập tên sản phẩm" value="{{ old('name') }}">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea name="description" id="description" rows="3" class="form-control" placeholder="Nhập mô tả">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Giá</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control"
                                placeholder="Nhập giá" value="{{ old('price') }}">
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Danh mục</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Ảnh sản phẩm</label>
                            <input type="file" name="thumbnail" id="thumbnail" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-success">Lưu</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

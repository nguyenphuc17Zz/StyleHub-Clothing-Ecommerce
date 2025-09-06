@extends('layouts.app')
@section('title', 'Thêm hình ảnh sản phẩm')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-lg-6 col-sm-12">
                <div class="white-box">
                    <h3 class="box-title">Thêm hình ảnh sản phẩm</h3>

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

                    <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="product_name" class="form-label">Tên sản phẩm</label>
                            <input type="text" name="product_name" id="product_name" class="form-control"
                                placeholder="Nhập tên sản phẩm..." autocomplete="off" value="{{ old('product_name') }}">
                            <input type="hidden" name="product_id" id="product_id" value="{{ old('product_id') }}">
                            <div id="product_suggestions" class="list-group mt-1"
                                style="position:absolute; width:100%; max-height:200px; overflow-y:auto; z-index:1000; background:white;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Chọn hình ảnh</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        </div>

                        <button type="submit" class="btn btn-success">Lưu</button>
                        <a href="{{ route('images.index') }}" class="btn btn-secondary">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById('product_name');
            const suggestions = document.getElementById('product_suggestions');
            const hiddenInput = document.getElementById('product_id');

            const products = @json($products);
            console.log(products);

            input.addEventListener('input', function() {
                const keyword = this.value.toLowerCase();
                suggestions.innerHTML = '';

                if (keyword.length < 1) {
                    hiddenInput.value = '';
                    return;
                }

                const filtered = products.filter(p => p.name.toLowerCase().includes(keyword));

                filtered.forEach(product => {
                    const item = document.createElement('a');
                    item.href = "#";
                    item.classList.add('list-group-item', 'list-group-item-action');
                    item.textContent = product.name;
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        input.value = product.name;
                        hiddenInput.value = product.id;
                        suggestions.innerHTML = '';
                    });
                    suggestions.appendChild(item);
                });
            });
        });
    </script>
@endsection

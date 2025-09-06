@extends('layouts.app')
@section('title', 'Chỉnh sửa người dùng')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-lg-6 col-sm-12">
                <div class="white-box">
                    <h3 class="box-title">Chỉnh sửa người dùng</h3>

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

                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Nhập tên"
                                value="{{ old('name', $user->name) }}">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                placeholder="Nhập email" value="{{ old('email', $user->email) }}">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu (để trống nếu không đổi)</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Nhập mật khẩu mới">
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Vai trò</label>
                            <select name="role" id="role" class="form-control">
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User
                                </option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">Lưu</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

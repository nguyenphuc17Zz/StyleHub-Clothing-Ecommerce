@extends('layouts.app')
@section('title', 'Chat Management')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="white-box">

                    <!-- Thanh search -->
                    <form method="GET" action="{{ route('chats.index') }}">
                        <div class="input-group mb-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Tìm kiếm theo tên user hoặc email..." value="{{ $keyword ?? '' }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-search"></i> Tìm
                            </button>
                        </div>
                    </form>

                    <h3 class="box-title">Danh sách Chat
                        {{-- nếu muốn thêm nút tạo chat thủ công, có thể bỏ comment --}}
                        {{-- <a href="{{ route('chats.create') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> Thêm mới
                    </a> --}}
                    </h3>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Admin</th>
                                    <th>Số tin nhắn</th>
                                    <th>Bắt đầu lúc</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($chats as $index => $chat)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $chat->user->name ?? 'N/A' }}</td>
                                        <td>{{ $chat->user->email ?? 'N/A' }}</td>
                                        <td>{{ $chat->admin->name ?? '-' }}</td>
                                        <td>{{ $chat->messages_count ?? $chat->messages->count() }}</td>
                                        <td>{{ $chat->started_at->format('d-m-Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('chats.show', $chat->id) }}" class="btn btn-sm btn-info">
                                                Xem chi tiết
                                            </a>
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

                    <div class="d-flex justify-content-center mt-3">
                        {{ $chats->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

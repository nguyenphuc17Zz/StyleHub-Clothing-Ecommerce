<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="{{ asset(path: 'assets/auth/register.css') }}" rel="stylesheet">

</head>

<body>
    <div class="register-container">
        <div class="register-box">
            <h2>Đăng Ký</h2>
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="input-group">
                    <label for="name">Họ và tên</label>
                    <input type="text" id="name" name="name" placeholder="Nhập họ và tên"
                        value="{{ old('name') }}" required />
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Nhập email"
                        value="{{ old('email') }}" required />
                </div>
                <div class="input-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required />
                </div>
                <div class="input-group">
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="Nhập lại mật khẩu" required />
                </div>

                @if ($errors->any())
                    <div class="error">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if (session('message'))
                    <p class="error-message">{{ session('message') }}</p>
                @endif

                <button type="submit" class="btn-register">Đăng ký</button>
            </form>

            <p class="login-text">
                Bạn đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
            </p>
        </div>
    </div>
    z
</body>

</html>

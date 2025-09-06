<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="{{ asset('assets/auth/login.css') }}" rel="stylesheet">

</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Đăng Nhập</h2>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Nhập email"
                        value="{{ old('email') }}" required />
                </div>
                <div class="input-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required />
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

                <button type="submit" class="btn-login">Đăng nhập</button>
            </form>

            <p class="register-text">
                Bạn chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký</a>
            </p>
        </div>
    </div>

</body>

</html>

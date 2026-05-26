<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f7fb;
            font-family: Arial, Helvetica, sans-serif;
        }
        .login-box {
            width: 390px;
            background: #fff;
            padding: 28px 26px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }
        .form-label {
            color: #777;
            font-size: 20px;
            margin-bottom: 4px;
        }
        .form-control {
            height: 50px;
            border-radius: 10px;
            font-size: 18px;
        }
        .form-control:focus {
            border-color: #66afe9;
            box-shadow: 0 0 0 .25rem rgba(13,110,253,.2);
        }
        .password-wrap {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            font-size: 22px;
            color: #222;
        }
        .forgot-link {
            color: #006fb6;
            font-style: italic;
            font-size: 18px;
            text-decoration: none;
        }
        .login-btn {
            background: #0b70a8;
            border-color: #0b70a8;
            border-radius: 9px;
            padding: 10px 35px;
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        @if ($errors->any())
            <div class="alert alert-danger py-2">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <h2 class="text-center fw-bold text-primary mb-4">
                ĐĂNG NHẬP
            </h2>
            <div class="mb-2">
                <label class="form-label">Tài khoản</label>
                <input type="text" name="username" value="{{ old('username') }}" class="form-control" autofocus>
            </div>

            <div class="mb-2">
                <label class="form-label">Mật khẩu</label>
                <div class="password-wrap">
                    <input type="password" name="password" id="password" class="form-control pe-5">
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i id="eyeIcon" class="bi bi-eye-slash-fill"></i>
                    </button>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary login-btn">Đăng nhập</button>
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-fill';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye-slash-fill';
            }
        }
    </script>
</body>
</html>

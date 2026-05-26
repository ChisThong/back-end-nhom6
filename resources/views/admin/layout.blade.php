<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quản lý thi trắc nghiệm</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;background:#f5f7fb;margin:0;color:#222}
        .topbar{background:#1f2937;color:white;padding:14px 24px;display:flex;justify-content:space-between;align-items:center}
        .topbar a{color:white;text-decoration:none;margin-left:14px}
        .container{max-width:1100px;margin:24px auto;background:white;padding:24px;border-radius:12px;box-shadow:0 4px 18px rgba(0,0,0,.08)}
        h1{margin-top:0;font-size:26px}.actions{margin:16px 0;display:flex;gap:10px;flex-wrap:wrap}
        table{width:100%;border-collapse:collapse;margin-top:15px}th,td{border:1px solid #ddd;padding:10px;text-align:left;vertical-align:top}th{background:#eef2ff}
        .btn{display:inline-block;padding:8px 12px;border-radius:6px;border:0;background:#2563eb;color:white;text-decoration:none;cursor:pointer;font-size:14px}
        .btn.gray{background:#6b7280}.btn.red{background:#dc2626}.btn.green{background:#16a34a}.btn.orange{background:#f97316}
        input,textarea,select{width:100%;padding:9px;margin-top:6px;margin-bottom:14px;border:1px solid #ccc;border-radius:6px;box-sizing:border-box}
        label{font-weight:bold}.alert{padding:12px;border-radius:6px;margin-bottom:15px}.success{background:#dcfce7;color:#166534}.error{background:#fee2e2;color:#991b1b}
        .small{font-size:13px;color:#555}.inline-form{display:inline}.question-box{background:#f9fafb;padding:12px;border-radius:8px;margin-bottom:12px}
    </style>
</head>
<body>
    <div class="topbar">
        <div><strong>Admin</strong> - Hệ thống thi trắc nghiệm</div>
        <div>
            <a href="{{ route('admin.quizzes.index') }}">Quản lý đề thi</a>
            <a href="{{ route('admin.questions.index') }}">Ngân hàng câu hỏi</a>
            <a href="{{ route('quiz.index') }}">Trang sinh viên</a>
        </div>
    </div>
    <div class="container">
        @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert error">{{ session('error') }}</div>@endif
        @if($errors->any())
            <div class="alert error">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif
        @yield('content')
    </div>
</body>
</html>

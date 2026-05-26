<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chọn Đề Thi Trắc Nghiệm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

     <nav class="navbar navbar-dark bg-primary shadow-sm mb-4">
        <div class="container-fluid px-4">
            <span class="navbar-brand mb-0 h1 fw-bold">
                <i class="bi bi-mortarboard-fill"></i> CỔNG THI SINH VIÊN
            </span>
            <span class="text-white fw-semibold">
                
                @if(Auth::check())
                    @php
                        $currentUser = Auth::user();
                    @endphp
                    <span class="me-3">
                        <i class="bi bi-person-circle"></i> Xin chào, {{ $currentUser->first_name }}!
                    </span>
                    <a href="{{ url('/logout') }}" class="btn btn-danger btn-sm fw-bold">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                @else
                    <a href="{{ route('login.form') }}" class="btn btn-light fw-bold text-primary">
                        Đăng nhập
                    </a>
                @endif

            </span>
        </div>
    </nav>

    <div class="container my-5">
        <h3 class="text-center fw-bold text-primary mb-4"><i class="fas fa-file-signature me-2"></i>DANH SÁCH ĐỀ THI TRẮC NGHIỆM</h3>
        
       <div class="row">
    @foreach($quizzes as $quiz)
    <div class="col-md-4 mb-3">
        <div class="card p-3 shadow-sm h-100">
            <div class="mb-2">
                <span class="badge bg-success-subtle text-success p-2">
                    <i class="bi bi-clock"></i> {{ $quiz->duration }} Phút
                </span>
            </div>
            
            <h5 class="fw-bold text-dark my-2" style="min-height: 50px;">
                {{ $quiz->quiz_name }}
            </h5>
            
            <p class="small text-muted" style="min-height: 40px;">
                {!! $quiz->description ?? 'Không có mô tả' !!}
            </p>
            
            <hr>
            
            <div class="d-flex justify-content-between align-items-center mt-auto">
                <span class="small text-secondary fw-semibold">
                    <i class="bi bi-list-task"></i> {{ $quiz->noq }} Câu hỏi
                </span>
                
<a href="{{ route('quiz.show', $quiz->quid) }}" class="btn btn-primary fw-bold px-3">                    VÀO THI &rarr;
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
    </div>

</body>
</html>
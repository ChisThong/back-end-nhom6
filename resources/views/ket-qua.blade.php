<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả bài thi - {{ $quiz->quiz_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .result-card { border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); padding: 30px; }
        .score-display { font-size: 3rem; font-weight: bold; color: #007bff; }
        .status-pass { color: #28a745; font-weight: bold; }
        .status-fail { color: #dc3545; font-weight: bold; }
    </style>
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

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card result-card">
                    <h2 class="text-center mb-4">Kết quả làm bài</h2>
                    <hr>
                    <div class="text-center">
                        <h4>Bài thi: <strong>{{ $quiz->quiz_name }}</strong></h4>
                        <div class="score-display my-4">
                        {{ round($result->score_obtained) }} / {{ round($quiz->noq * 1) }} 
                            </div>
                        
                        <p>Phần trăm đạt được: <strong>{{ number_format($result->percentage_obtained, 2) }}%</strong></p>
                        
                        <p>Trạng thái: 
                            <span class="{{ $result->result_status == 'Pass' ? 'status-pass' : 'status-fail' }}">
                                {{ $result->result_status == 'Pass' ? 'ĐẠT' : 'CHƯA ĐẠT' }}
                            </span>
                        </p>
                    </div>

                    <div class="mt-4">
                        <table class="table table-bordered">
                            <tr>
                                <td>Thời gian bắt đầu</td>
                                <td>{{ date('H:i:s d/m/Y', $result->start_time) }}</td>
                            </tr>
                            <tr>
                                <td>Tổng thời gian làm bài</td>
                                <td>{{ gmdate("i:s", $result->total_time) }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ url('/') }}" class="btn btn-primary px-4">Trang chủ</a>
                        <a href="{{ route('quiz.index') }}" class="btn btn-secondary px-4">Thi bài khác</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
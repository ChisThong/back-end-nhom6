<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quiz->quiz_name }} - Phòng Thi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .q-number-btn {
            width: 40px;
            height: 40px;
            margin: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s ease;
        }
        .q-number-btn:hover {
            transform: scale(1.1);
        }
        .question-box {
            border-left: 5px solid #6c757d;
            scroll-margin-top: 30px;
        }
        .question-box.answered {
            border-left: 5px solid #198754;
        }
        .style-label {
            cursor: pointer;
            display: block;
            transition: background-color 0.2s;
        }
        .style-label:hover {
            background-color: #f0f2f5;
        }
        .sticky-sidebar {
            position: sticky;
            top: 20px;
        }
    </style>
</head>
<body class="bg-light">

   <nav class="navbar navbar-dark bg-primary shadow-sm mb-4">
        <div class="container-fluid px-4">
            <span class="navbar-brand mb-0 h1 fw-bold">
                <i class="bi bi-mortarboard-fill"></i> CỔNG THI SINH VIÊN
            </span>
            <span class="text-white fw-semibold">
                
                @if(auth()->check())
                    @php
                        $currentUser = auth()->user();
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

    <div class="container-fluid px-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card p-3 shadow-sm border-0 bg-white">
                    <h3 class="text-primary mb-0 fw-bold">{{ $quiz->quiz_name }}</h3>
                    @if($quiz->description)
                        <div class="text-muted small mt-2">{!! $quiz->description !!}</div>
                    @endif
                </div>
            </div>
        </div>

        <form id="form-exam-submit" action="{{ route('quiz.submit', $quiz->quid) }}" method="POST">
            @csrf
            <input type="hidden" id="total_time" name="total_time" value="0">

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card p-3 shadow-sm border-0 sticky-sidebar bg-white">
                        <div class="text-center mb-3">
                            <h6 class="text-secondary uppercase fw-bold text-muted">Thời gian còn lại</h6>
                            <h1 id="countdown-timer" class="text-danger fw-bold display-5">00:00</h1>
                        </div>
                        
                        <hr class="text-muted">
                        
                        <h6 class="fw-bold text-dark mb-2">Sơ đồ câu hỏi</h6>
                        <p class="small text-muted mb-3">Màu xanh: Đã chọn | Màu xám: Chưa làm</p>
                        
                        <div class="d-flex flex-wrap mb-3">
                            @foreach($questions as $index => $question)
                                <div id="nav-q-{{ $question->qid }}" 
                                     class="q-number-btn bg-secondary text-white" 
                                     onclick="scrollToQuestion('q-box-{{ $question->qid }}')">
                                    {{ $index + 1 }}
                                </div>
                            @endforeach
                        </div>

                        <hr class="text-muted">
                        
                        <button type="button" class="btn btn-danger w-100 fw-bold py-2 shadow-sm fs-5" onclick="submitExam(false)">
                            NỘP BÀI THI
                        </button>
                    </div>
                </div>

                <div class="col-md-8">
                    @if(count($questions) == 0)
                        <div class="alert alert-warning text-center fw-bold shadow-sm">
                            Đề thi này hiện tại chưa được cấu hình câu hỏi trong cơ sở dữ liệu!
                        </div>
                    @else
                        @foreach($questions as $index => $question)
                            <div id="q-box-{{ $question->qid }}" class="card p-4 mb-4 shadow-sm border-0 question-box bg-white">
                                <h5 class="fw-bold text-dark mb-3">
                                    Câu {{ $index + 1 }}: {!! $question->question !!}
                                </h5>
                                
                             <div class="options-group ps-2">
                                @foreach($question->options as $option)
                                    <div class="form-check my-3">
                                        <input class="form-check-input answer-radio" 
                                            type="radio" 
                                            name="ans[{{ $question->qid }}]" 
                                            data-qid="{{ $question->qid }}"
                                            data-index="{{ $index }}"
                                            id="opt-{{ $option->oid }}-{{ $index }}" 
                                            value="{{ $option->oid }}">
                                        
                                        <label class="form-check-label w-100 p-2 rounded style-label" for="opt-{{ $option->oid }}-{{ $index }}">
                                            {{ $option->q_option }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bundle.min.js"></script>

    <script>
        // Lấy thời gian làm bài động trực tiếp từ cột duration của đề thi (quy đổi sang giây)
        const quizId = "{{ $quiz->quid }}";
        const totalDurationMinutes = parseInt("{{ $quiz->duration }}") || 10;
        let examDurationSeconds = totalDurationMinutes * 60;
        let timerInterval;

        document.addEventListener("DOMContentLoaded", function() {
            // Thiết lập bộ đếm thời gian an toàn thông qua LocalStorage (chống mất thời gian khi F5)
            handleTimerSetup();
            // Khôi phục đáp án đã làm trước đó nếu lỡ tay tải lại trang
            restoreSavedAnswers();

            // Lắng nghe sự kiện click chọn đáp án để cập nhật trạng thái màu sắc sơ đồ
            document.querySelectorAll('.answer-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    const qid = this.dataset.qid;
                    const oid = this.value;
                    
                    markQuestionAsDone(qid);
                    saveAnswerLocally(qid, oid);
                });
            });
        });

        // Xử lý thiết lập bộ đếm thời gian
        function handleTimerSetup() {
            const storageTimerKey = `quiz_timer_${quizId}`;
            let savedTime = localStorage.getItem(storageTimerKey);

            if (savedTime !== null) {
                examDurationSeconds = parseInt(savedTime);
            } else {
                localStorage.setItem(storageTimerKey, examDurationSeconds);
            }
            startCountdown();
        }

        // Đồng hồ đếm ngược chạy mượt mà theo từng giây
        function startCountdown() {
            const timerDisplay = document.getElementById('countdown-timer');
            const storageTimerKey = `quiz_timer_${quizId}`;

            clearInterval(timerInterval);
            timerInterval = setInterval(() => {
                let minutes = Math.floor(examDurationSeconds / 60);
                let seconds = examDurationSeconds % 60;

                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;

                timerDisplay.innerText = `${minutes}:${seconds}`;
                localStorage.setItem(storageTimerKey, examDurationSeconds);

                // Tính toán tổng thời gian đã làm (bằng giây) đẩy vào input ẩn để gửi lên controller
                let timeSpent = (totalDurationMinutes * 60) - examDurationSeconds;
                document.getElementById('total_time').value = timeSpent;

                if (--examDurationSeconds < 0) {
                    clearInterval(timerInterval);
                    localStorage.removeItem(storageTimerKey);
                    localStorage.removeItem(`quiz_ans_${quizId}`);
                    timerDisplay.innerText = "HẾT GIỜ!";
                    alert("Thời gian làm bài của bạn đã hết! Hệ thống tự động nộp bài thi.");
                    submitExam(true); // Ép buộc nộp bài không hiển thị confirm khi hết giờ
                }
            }, 1000);
        }

        // Đổi màu sơ đồ câu hỏi (Menu trái) và khối câu hỏi (Phải) sang màu xanh lá
        function markQuestionAsDone(qid) {
            const navBtn = document.getElementById(`nav-q-${qid}`);
            const qBox = document.getElementById(`q-box-${qid}`);
            if (navBtn) {
                navBtn.classList.remove('bg-secondary');
                navBtn.classList.add('bg-success');
            }
            if (qBox) {
                qBox.classList.add('answered');
            }
        }

        // Lưu tạm đáp án vào bộ nhớ trình duyệt đề phòng sự cố lag mạng hoặc F5 đột ngột
        function saveAnswerLocally(qid, oid) {
            let localAnswers = JSON.parse(localStorage.getItem(`quiz_ans_${quizId}`)) || {};
            localAnswers[qid] = oid;
            localStorage.setItem(`quiz_ans_${quizId}`, JSON.stringify(localAnswers));
        }

        // Khôi phục trạng thái tick chọn các phương án từ bộ nhớ trình duyệt
        function restoreSavedAnswers() {
            let localAnswers = JSON.parse(localStorage.getItem(`quiz_ans_${quizId}`)) || {};
            Object.keys(localAnswers).forEach(qid => {
                const oid = localAnswers[qid];
                const radioTarget = document.getElementById(`opt-${oid}`);
                if (radioTarget) {
                    radioTarget.checked = true;
                    markQuestionAsDone(qid);
                }
            });
        }

        // Cuộn mượt màn hình tới vị trí câu hỏi tương ứng khi click vào nút sơ đồ
        function scrollToQuestion(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Hàm kích hoạt gửi Form chính thức lên Laravel Controller để chấm điểm thật
        function submitExam(isForce = false) {
            if (!isForce && !confirm("Bạn có chắc chắn muốn kết thúc bài làm và nộp bài thi này không?")) {
                return;
            }

            clearInterval(timerInterval);
            
            // Xóa sạch bộ nhớ tạm thời của trình duyệt cho đề thi hiện tại khi đã bấm nộp thành công
            localStorage.removeItem(`quiz_timer_${quizId}`);
            localStorage.removeItem(`quiz_ans_${quizId}`);

            // Gửi dữ liệu form một cách đồng bộ lên server
            document.getElementById('form-exam-submit').submit();
        }
    </script>
</body>
</html>
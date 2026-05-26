@extends('admin.layout')

@section('content')
<h1>Thêm đề thi</h1>
<p class="small">Hiện có {{ $questionCount }} câu hỏi trong ngân hàng. Khi lưu, hệ thống sẽ tự random câu hỏi và lưu vào cột qids.</p>
<form action="{{ route('admin.quizzes.store') }}" method="POST">
    @csrf
    <label>Tên đề thi</label>
    <input type="text" name="quiz_name" value="{{ old('quiz_name') }}" required>

    <label>Mô tả</label>
    <textarea name="description" rows="3">{{ old('description') }}</textarea>

    <label>Thời gian làm bài (phút)</label>
    <input type="number" name="duration" min="1" value="{{ old('duration', 30) }}" required>

    <label>Số câu hỏi cần random</label>
    <input type="number" name="noq" min="1" value="{{ old('noq', 10) }}" required>

    <label>Điểm mỗi câu đúng</label>
    <input type="number" step="0.1" name="correct_score" min="0" value="{{ old('correct_score', 1) }}" required>

    <label>Điểm trừ mỗi câu sai</label>
    <input type="number" step="0.1" name="incorrect_score" min="0" value="{{ old('incorrect_score', 0) }}" required>

    <label>Phần trăm đạt (%)</label>
    <input type="number" step="0.1" name="pass_percentage" min="0" max="100" value="{{ old('pass_percentage', 50) }}" required>

    <button class="btn green" type="submit">Lưu và random câu hỏi</button>
    <a href="{{ route('admin.quizzes.index') }}" class="btn gray">Quay lại</a>
</form>
@endsection

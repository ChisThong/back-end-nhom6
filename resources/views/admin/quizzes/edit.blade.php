@extends('admin.layout')

@section('content')
<h1>Sửa đề thi</h1>
<form action="{{ route('admin.quizzes.update', $quiz->quid) }}" method="POST">
    @csrf
    @method('PUT')
    <label>Tên đề thi</label>
    <input type="text" name="quiz_name" value="{{ old('quiz_name', $quiz->quiz_name) }}" required>

    <label>Mô tả</label>
    <textarea name="description" rows="3">{{ old('description', $quiz->description) }}</textarea>

    <label>Thời gian làm bài (phút)</label>
    <input type="number" name="duration" min="1" value="{{ old('duration', $quiz->duration) }}" required>

    <label>Số câu</label>
    <input type="number" value="{{ $quiz->noq }}" disabled>
    <p class="small">Muốn đổi danh sách câu hỏi thì bấm Random lại ở trang danh sách đề thi.</p>

    <label>Điểm mỗi câu đúng</label>
    <input type="number" step="0.1" name="correct_score" min="0" value="{{ old('correct_score', explode(',', (string)$quiz->correct_score)[0] ?? 1) }}" required>

    <label>Điểm trừ mỗi câu sai</label>
    <input type="number" step="0.1" name="incorrect_score" min="0" value="{{ old('incorrect_score', explode(',', (string)$quiz->incorrect_score)[0] ?? 0) }}" required>

    <label>Phần trăm đạt (%)</label>
    <input type="number" step="0.1" name="pass_percentage" min="0" max="100" value="{{ old('pass_percentage', $quiz->pass_percentage) }}" required>

    <label>Danh sách mã câu hỏi hiện tại</label>
    <textarea rows="2" disabled>{{ $quiz->qids }}</textarea>

    <button class="btn" type="submit">Cập nhật</button>
    <a href="{{ route('admin.quizzes.index') }}" class="btn gray">Quay lại</a>
</form>
@endsection

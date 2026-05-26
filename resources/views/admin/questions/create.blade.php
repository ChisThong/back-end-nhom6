@extends('admin.layout')

@section('content')
<h1>Thêm câu hỏi</h1>
<form action="{{ route('admin.questions.store') }}" method="POST">
    @csrf
    <label>Nội dung câu hỏi</label>
    <textarea name="question" rows="4" required>{{ old('question') }}</textarea>

    <label>Ghi chú / mô tả</label>
    <textarea name="description" rows="2">{{ old('description') }}</textarea>

    @for($i = 0; $i < 4; $i++)
        <div class="question-box">
            <label>Đáp án {{ $i + 1 }}</label>
            <input type="text" name="options[{{ $i }}]" value="{{ old('options.'.$i) }}" required>
            <label><input style="width:auto" type="radio" name="correct_option" value="{{ $i }}" {{ old('correct_option', 0) == $i ? 'checked' : '' }}> Đây là đáp án đúng</label>
        </div>
    @endfor

    <button class="btn green" type="submit">Lưu câu hỏi</button>
    <a href="{{ route('admin.questions.index') }}" class="btn gray">Quay lại</a>
</form>
@endsection

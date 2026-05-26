@extends('admin.layout')

@section('content')
<h1>Sửa câu hỏi</h1>
@php
    $options = $question->options->values();
    $correctIndex = 0;
    foreach($options as $idx => $opt){ if($opt->score > 0){ $correctIndex = $idx; break; } }
@endphp
<form action="{{ route('admin.questions.update', $question->qid) }}" method="POST">
    @csrf
    @method('PUT')
    <label>Nội dung câu hỏi</label>
    <textarea name="question" rows="4" required>{{ old('question', $question->question) }}</textarea>

    <label>Ghi chú / mô tả</label>
    <textarea name="description" rows="2">{{ old('description', $question->description) }}</textarea>

    @for($i = 0; $i < 4; $i++)
        <div class="question-box">
            <label>Đáp án {{ $i + 1 }}</label>
            <input type="text" name="options[{{ $i }}]" value="{{ old('options.'.$i, optional($options->get($i))->q_option) }}" required>
            <label><input style="width:auto" type="radio" name="correct_option" value="{{ $i }}" {{ old('correct_option', $correctIndex) == $i ? 'checked' : '' }}> Đây là đáp án đúng</label>
        </div>
    @endfor

    <button class="btn" type="submit">Cập nhật câu hỏi</button>
    <a href="{{ route('admin.questions.index') }}" class="btn gray">Quay lại</a>
</form>
@endsection

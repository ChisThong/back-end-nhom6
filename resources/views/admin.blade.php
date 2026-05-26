@extends('admin.layout')

@section('content')
    <h1>Trang quản trị</h1>
    <p>Chọn chức năng cần test:</p>
    <div class="actions">
        <a class="btn" href="{{ route('admin.quizzes.index') }}">Quản lý đề thi</a>
        <a class="btn green" href="{{ route('admin.questions.index') }}">Quản lý ngân hàng câu hỏi</a>
    </div>
@endsection

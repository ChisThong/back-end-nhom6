@extends('admin.layout')

@section('content')
<h1>Quản lý ngân hàng câu hỏi</h1>
<div class="actions">
    <a href="{{ route('admin.questions.create') }}" class="btn green">+ Thêm câu hỏi</a>
    <a href="{{ route('admin.quizzes.index') }}" class="btn gray">Quản lý đề thi</a>
</div>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Câu hỏi</th>
            <th>Đáp án</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @forelse($questions as $question)
            <tr>
                <td>{{ $question->qid }}</td>
                <td>{!! $question->question !!}<br><span class="small">{{ $question->description }}</span></td>
                <td>
                    @foreach($question->options as $option)
                        <div>@if($option->score > 0) ✅ @else ◻️ @endif {{ $option->q_option }}</div>
                    @endforeach
                </td>
                <td>
                    <a class="btn" href="{{ route('admin.questions.edit', $question->qid) }}">Sửa</a>
                    <form class="inline-form" action="{{ route('admin.questions.destroy', $question->qid) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn red" onclick="return confirm('Xoá câu hỏi này?')">Xoá</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">Chưa có câu hỏi.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

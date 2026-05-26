@extends('admin.layout')

@section('content')
<h1>Quản lý đề thi</h1>
<div class="actions">
    <a href="{{ route('admin.quizzes.create') }}" class="btn green">+ Thêm đề thi</a>
    <a href="{{ route('admin.questions.index') }}" class="btn gray">Ngân hàng câu hỏi</a>
</div>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên đề thi</th>
            <th>Thời gian</th>
            <th>Số câu</th>
            <th>Điểm/câu</th>
            <th>QIDs random</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @forelse($quizzes as $quiz)
            <tr>
                <td>{{ $quiz->quid }}</td>
                <td><strong>{{ $quiz->quiz_name }}</strong><br><span class="small">{{ $quiz->description }}</span></td>
                <td>{{ $quiz->duration }} phút</td>
                <td>{{ $quiz->noq }}</td>
                <td>{{ explode(',', (string)$quiz->correct_score)[0] ?? 0 }}</td>
                <td class="small">{{ $quiz->qids }}</td>
                <td>
                    <a class="btn" href="{{ route('admin.quizzes.edit', $quiz->quid) }}">Sửa</a>
                    <form class="inline-form" action="{{ route('admin.quizzes.random', $quiz->quid) }}" method="POST">
                        @csrf
                        <button class="btn orange" onclick="return confirm('Random lại câu hỏi cho đề này?')">Random lại</button>
                    </form>
                    <form class="inline-form" action="{{ route('admin.quizzes.destroy', $quiz->quid) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn red" onclick="return confirm('Xoá đề thi này?')">Xoá</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7">Chưa có đề thi.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

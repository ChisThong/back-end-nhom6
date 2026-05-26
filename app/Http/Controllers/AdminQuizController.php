<?php

namespace App\Http\Controllers;

use App\Models\QBank;
use App\Models\Quizz;
use Illuminate\Http\Request;

class AdminQuizController extends Controller
{

    public function index()
    {
        $quizzes = Quizz::orderBy('quid', 'desc')->get();
        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $questionCount = QBank::count();
        return view('admin.quizzes.create', compact('questionCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'quiz_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'noq' => 'required|integer|min:1',
            'correct_score' => 'required|numeric|min:0',
            'incorrect_score' => 'required|numeric|min:0',
            'pass_percentage' => 'required|numeric|min:0|max:100',
        ], [
            'quiz_name.required' => 'Vui lòng nhập tên đề thi.',
            'duration.required' => 'Vui lòng nhập thời gian làm bài.',
            'noq.required' => 'Vui lòng nhập số câu hỏi.',
        ]);

        $numberOfQuestions = (int) $request->noq;

        // Random mã câu hỏi từ ngân hàng câu hỏi
        $questionIds = QBank::inRandomOrder()
            ->limit($numberOfQuestions)
            ->pluck('qid')
            ->toArray();

        if (count($questionIds) < $numberOfQuestions) {
            return back()
                ->withInput()
                ->with('error', 'Ngân hàng câu hỏi chưa đủ số lượng câu hỏi để tạo đề.');
        }

        $quiz = new Quizz();
        $quiz->quiz_name = $request->quiz_name;
        $quiz->description = $request->description;
        $quiz->duration = $request->duration;
        $quiz->noq = $numberOfQuestions;
        $quiz->qids = implode(',', $questionIds);
        $quiz->correct_score = $this->repeatScore($request->correct_score, $numberOfQuestions);
        $quiz->incorrect_score = $this->repeatScore($request->incorrect_score, $numberOfQuestions);
        $quiz->pass_percentage = $request->pass_percentage;

        // Giá trị mặc định để phù hợp cấu trúc savsoft_quiz
        $quiz->start_date = now()->format('Y-m-d H:i:s');
        $quiz->end_date = now()->addYear()->format('Y-m-d H:i:s');
        $quiz->gids = '0';
        $quiz->maximum_attempts = 1;
        $quiz->view_answer = 1;
        $quiz->camera_req = 0;
        $quiz->question_selection = 0;
        $quiz->gen_certificate = 0;
        $quiz->with_login = 1;
        $quiz->demo = 0;
        $quiz->quiz_template = 'default';
        $quiz->certificate_text = '';
        $quiz->ip_address = '';

        $quiz->save();

        return redirect()
            ->route('admin.quizzes.index')
            ->with('success', 'Thêm đề thi thành công. Hệ thống đã random câu hỏi vào đề.');
    }

    public function edit($id)
    {
        $quiz = Quizz::findOrFail($id);
        return view('admin.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, $id)
    {
        $quiz = Quizz::findOrFail($id);

        $request->validate([
            'quiz_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'correct_score' => 'required|numeric|min:0',
            'incorrect_score' => 'required|numeric|min:0',
            'pass_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $numberOfQuestions = count(array_filter(explode(',', (string) $quiz->qids)));
        if ($numberOfQuestions <= 0) {
            $numberOfQuestions = (int) ($quiz->noq ?: 1);
        }

        $quiz->quiz_name = $request->quiz_name;
        $quiz->description = $request->description;
        $quiz->duration = $request->duration;
        $quiz->correct_score = $this->repeatScore($request->correct_score, $numberOfQuestions);
        $quiz->incorrect_score = $this->repeatScore($request->incorrect_score, $numberOfQuestions);
        $quiz->pass_percentage = $request->pass_percentage;
        $quiz->save();

        return redirect()
            ->route('admin.quizzes.index')
            ->with('success', 'Cập nhật đề thi thành công.');
    }

    public function destroy($id)
    {
        Quizz::findOrFail($id)->delete();

        return redirect()
            ->route('admin.quizzes.index')
            ->with('success', 'Xoá đề thi thành công.');
    }

    public function randomQuestions($id)
    {
        $quiz = Quizz::findOrFail($id);
        $numberOfQuestions = (int) ($quiz->noq ?: 1);

        $questionIds = QBank::inRandomOrder()
            ->limit($numberOfQuestions)
            ->pluck('qid')
            ->toArray();

        if (count($questionIds) < $numberOfQuestions) {
            return back()->with('error', 'Ngân hàng câu hỏi chưa đủ số lượng câu hỏi để random lại đề.');
        }

        $quiz->qids = implode(',', $questionIds);
        $quiz->save();

        return back()->with('success', 'Đã random lại câu hỏi cho đề thi.');
    }

    private function repeatScore($score, int $numberOfQuestions): string
    {
        return implode(',', array_fill(0, $numberOfQuestions, $score));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Quizz;

use Illuminate\Http\Request;

class QizzController extends Controller
{
    public function index()
    {
        $quizzes = Quizz::select(['quid', 'quiz_name', 'description', 'start_date', 'end_date', 'duration', 'noq'])->orderBy('quid', 'desc')->get();
        return view('chon-de', compact('quizzes'));
    }
    public function show($id)
    {
        $quiz = Quizz::findOrFail($id);
        $questions = $quiz->getQuestions();
        return view('thi', compact('quiz', 'questions'));
    }
    public function submit(Request $request, $id)
    {
        $quiz = Quizz::with('questions.options')->findOrFail($id);
        $studentAnswers = $request->input('answer', []);
        $score = 0;
        $total = $quiz->questions->count();
        foreach ($quiz->questions as $question) {
            $correctOption = $question->options->where('q_option_match', 1)->first();
            if (isset($studentAnswers[$question->qid])) {
                $selectedOid = $studentAnswers[$question->qid];
                if ($correctOption && $selectedOid == $correctOption->oid) {
                    $score++;
                }
            }
        }
        session(['last_score' => $score, 'last_total' => $total]);
        return redirect()->route('ket-qua');
    }
    public function ketQua()
    {
        $score = session('last_score', 0);
        $total = session('last_total', 0);
        return view('ket-qua', compact('score', 'total'));
    }
}

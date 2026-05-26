<?php

namespace App\Http\Controllers;

use App\Models\Quizz;

use Illuminate\Http\Request;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;

class QizzController extends Controller
{
    public function index()
    {
        $quizzes = Quizz::select(['quid', 'quiz_name', 'description', 'start_date', 'end_date', 'duration', 'noq'])->orderBy('quid', 'desc')->get();
        return view('chon-de', compact('quizzes'));
    }
    public function show(String $id)
    {   
        if(!Auth::check()){
            return redirect('/login')->withErrors(['login_error' => 'Vui lòng đăng nhập để tham gia thi']);
        }
        $quiz = Quizz::findOrFail($id);
        $questions = $quiz->getQuestions();
        session(['quiz_start_time' => time()]);
        
        return view('thi', compact('quiz', 'questions'));
    }
    public function submit(Request $request, String $quid)
    {
        $quiz = Quizz::findOrFail($quid);
        $startTime = session('quiz_start_time', time());
        $questions = $quiz->getQuestions(); 
        $correctScores = explode(',', $quiz->correct_score);
        $incorrectScores = explode(',', $quiz->incorrect_score);
        
        $userAnswers = $request->input('ans', []);
        $score = 0;
        foreach ($questions as $index => $question) {
            $cScore = isset($correctScores[$index]) ? (int)$correctScores[$index] : (int)$correctScores[0];
            $iScore = isset($incorrectScores[$index]) ? (int)$incorrectScores[$index] : (int)$incorrectScores[0];
            
            $userChoice = $userAnswers[$question->qid] ?? null;
            $correctOption = $question->options->where('score', '>', 0)->first();
            
            if ($userChoice && $correctOption && $userChoice == $correctOption->oid) {
                $score += $cScore; 
            } else {
                $score -= $iScore; 
            }
        }
        $score = max(0, $score);
        $totalPossibleScore = $quiz->duration;
        $percentage = ($totalPossibleScore > 0) ? ($score / $totalPossibleScore) * 100 : 0;
        $result = Result::create([
            'quid' => $quid,
            'uid' => Auth::id(),
            'score_obtained' => $score,
            'percentage_obtained' => $percentage,
            'result_status' => ($percentage >= $quiz->pass_percentage) ? 'Pass' : 'Fail',
            'start_time' => $startTime,
            'end_time' => time(),
            'total_time' => (int)$request->input('total_time', 0),
            'attempted_ip' => $request->ip(),
            'categories' => $quiz->gids ?? '0',
            'r_qids' => $quiz->qids ?? '0',
            'category_range' => '0',
            'individual_time' => '0',
            'score_individual' => '0',
            'photo' => 'none',
            'manual_valuation' => 0,
        ]);
        return redirect()->route('quiz.result', $result->rid)
                        ->with('success', 'Bạn đã nộp bài thành công!');
    }
    public function result(String $rid)
    {
        $result = Result::findOrFail($rid);
        $quiz = Quizz::findOrFail($result->quid);
        return view('ket-qua', compact('result', 'quiz'));
    }
}

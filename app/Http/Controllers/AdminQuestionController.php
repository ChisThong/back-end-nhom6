<?php

namespace App\Http\Controllers;

use App\Models\Options;
use App\Models\QBank;
use Illuminate\Http\Request;

class AdminQuestionController extends Controller
{
 

    public function index()
    {
        $questions = QBank::with('options')->orderBy('qid', 'desc')->get();
        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        return view('admin.questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0|max:3',
        ], [
            'question.required' => 'Vui lòng nhập nội dung câu hỏi.',
            'options.*.required' => 'Vui lòng nhập đầy đủ đáp án.',
            'correct_option.required' => 'Vui lòng chọn đáp án đúng.',
        ]);

        $question = new QBank();
        $question->question_type = 1;
        $question->question = $request->question;
        $question->description = $request->description ?? '';
        $question->cid = 0;
        $question->lid = 0;
        $question->no_time_served = 0;
        $question->no_time_corrected = 0;
        $question->no_time_incorrected = 0;
        $question->no_time_unattempted = 0;
        $question->save();

        foreach ($request->options as $index => $optionText) {
            Options::create([
                'qid' => $question->qid,
                'q_option' => $optionText,
                'q_option_match' => '',
                'score' => ((int) $request->correct_option === (int) $index) ? 1 : 0,
            ]);
        }

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'Thêm câu hỏi thành công.');
    }

    public function edit($id)
    {
        $question = QBank::with('options')->findOrFail($id);
        return view('admin.questions.edit', compact('question'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0|max:3',
        ]);

        $question = QBank::findOrFail($id);
        $question->question = $request->question;
        $question->description = $request->description ?? '';
        $question->save();

        Options::where('qid', $question->qid)->delete();

        foreach ($request->options as $index => $optionText) {
            Options::create([
                'qid' => $question->qid,
                'q_option' => $optionText,
                'q_option_match' => '',
                'score' => ((int) $request->correct_option === (int) $index) ? 1 : 0,
            ]);
        }

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'Cập nhật câu hỏi thành công.');
    }

    public function destroy($id)
    {
        $question = QBank::findOrFail($id);
        Options::where('qid', $question->qid)->delete();
        $question->delete();

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'Xoá câu hỏi thành công.');
    }
}

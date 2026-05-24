<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quizz extends Model
{
    protected $table = 'savsoft_quiz';
    protected $primaryKey = 'quid';
    public $timestamps = false;

    protected $fillable = [
        'quiz_name',
        'description',
        'start_date',
        'end_date',
        'gids',
        'qids',
        'noq',
        'correct_score',
        'incorrect_score',
        'ip_address',
        'duration',
        'maximum_attempts',
        'pass_percentage',
        'view_answer',
        'camera_req',
        'question_selection',
        'gen_certificate',
        'certificate_text',
        'with_login',
        'quiz_template',
        'demo',
    ];

      public function getQuestions()
    {
        $ids = explode(',', $this->qids);
        return QBank::with('options')->whereIn('qid', $ids)->get();
    }
}

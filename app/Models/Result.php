<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    protected $table = 'savsoft_result';
    protected $primaryKey = 'rid';
    public $timestamps = false;
    protected $fillable = [
        'quid',
        'uid',
        'result_status',
        'start_time',
        'end_time',
        'categories',
        'category_range',
        'r_qids',
        'individual_time',
        'total_time',
        'score_obtained',
        'percentage_obtained',
        'attempted_ip',
        'score_individual',
        'photo',
        'manual_valuation',
    ];
    public function quiz() {
        return $this->belongsTo(Quizz::class, 'quid', 'quid');
    }
}

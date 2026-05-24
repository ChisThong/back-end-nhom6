<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QBank extends Model
{
    protected $table = 'savsoft_qbank';
    protected $primaryKey = 'qid';
    public $timestamps = false;

    protected $fillable = [
        'question_type',
        'question',
        'description',
        'cid',
        'lid',
        'no_time_served',
        'no_time_corrected',
        'no_time_incorrected',
        'no_time_unattempted',
    ];

    public function options()
{

    return $this->hasMany(Options::class, 'qid', 'qid');
}
}

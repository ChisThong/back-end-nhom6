<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Options extends Model
{
    protected $table = 'savsoft_options';
    protected $primaryKey = 'oid';
    public $timestamps = false;

    protected $fillable = [
        'qid',
        'q_option',
        'q_option_match',
        'score'
    ];
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
    use HasFactory;
    protected $table = 'savsoft_users';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $fillable = [
            'email',
            'password',
            'su',
            'first_name',
            'last_name',
            'contact_no',
            'connection_key',
            'gid',
            'su',
            'subscription_expired',
            'verify_code',
            'wp_user',
            'registered_date',
            'photo',
            'user_status',
            'web_token',
            'android_token',

            'studentid',
            'classid',
            'facultyid',
            'birthday',
            'type',
            'note',
            'academic_year',
            'qrcode',
           
        ];

        public function khoa()
        {
            return $this->belongsTo(Khoa::class, 'facultyid', 'facultyid');
        }
    
}

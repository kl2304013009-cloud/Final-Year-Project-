<?php

namespace App\Models;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    protected $primaryKey = 'student_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'name',
        'class',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}

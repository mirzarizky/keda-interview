<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticable
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected $fillable = [
        'email',
        'user_type_id',
        'password',
    ];

    protected $hidden = [
        'password'
    ];
}

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

    protected $with = ['type'];

    // Helpers
    public function isStaff()
    {
        return ($this->user_type_id == 2);
    }

    // Scopes
    public function scopeTypeCustomer($query)
    {
        return $query->where('user_type_id', 1);
    }

    // Relationships
    public function type()
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function rules(): array 
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:users,login',
            'password' => 'required',
        ];
    }

    public function feedback(): array 
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'login.unique' => 'Este login já está cadastrado no nosso sistema, informe outro.'
        ];
    }

    public function getJWTIdentifier(): string
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function loginError(): HasOne
    {
        return $this->hasOne(LoginError::class);
    }
}

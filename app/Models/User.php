<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Importa el trait HasApiTokens

class User extends Authenticatable
{
    use Notifiable, HasApiTokens; // Agrega HasApiTokens aquí

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'password',
        'status',
        'provider'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    /**
     * The attributes that should be cast to native types.
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];
}

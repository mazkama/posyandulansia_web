<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;  

    protected $table = 'users'; 

    protected $fillable = [
        'username',
        'password',
        'role',
        'email_verified_at', 
    ];
    protected $hidden = ['password'];

    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id');
    }
    public function lansia()
    {
        return $this->hasOne(Lansia::class, 'user_id');
    }

    public function kader()
    {
        return $this->hasOne(Kader::class, 'user_id');
    }
}



// class User extends Authenticatableb
// {
//     use HasApiTokens, HasFactory, Notifiable;

//     /**
//      * The attributes that are mass assignable.
//      *
//      * @var array<int, string>
//      */
//     protected $fillable = [
//         'name',
//         'email',
//         'password',
//     ];

//     /**
//      * The attributes that should be hidden for serialization.
//      *
//      * @var array<int, string>
//      */
//     protected $hidden = [
//         'password',
//         'remember_token',
//     ];

//     /**
//      * The attributes that should be cast.
//      *
//      * @var array<string, string>
//      */
//     protected $casts = [
//         'email_verified_at' => 'datetime',
//     ];
// }

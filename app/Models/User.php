<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            if ($user->email === 'SuperAdmin@example.com') {
                $user->role = 'superadmin';
            }
            elseif($user->email === 'Admin@gmail.com'){
                $user->role = 'admin';
            }
            else {
                $user->role = 'user';
            }
        });
    }
    public function isAdmin()
    {
        return $this->role === 'admin'; // Ganti 'role' sesuai nama kolom di tabel users kamu
    }
        public function isUser()
    {
        return $this->role === 'user'; // Ganti 'role' sesuai nama kolom di tabel users kamu
    }
        public function isSuperAdmin()
    {
        return $this->role === 'superadmin'; // Ganti 'role' sesuai nama kolom di tabel users kamu
    }
}
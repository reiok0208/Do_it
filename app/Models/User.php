<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function declaration() {
        return $this->hasMany(Declaration::class);
    }

    public function declaration_comment() {
        return $this->hasMany(Declaration_comment::class);
    }

    public function do_it() {
        return $this->hasMany(Do_it::class);
    }

    public function good_work() {
        return $this->hasMany(Good_work::class);
    }

    /**
     * Relationships
     */
    public function follows() //フォローした
    {
        return $this->belongsToMany(User::class, 'relationships', 'following_user_id', 'user_id');
    }

    public function followers() //フォローされた
    {
        return $this->belongsToMany(User::class, 'relationships', 'user_id', 'following_user_id');
    }
}

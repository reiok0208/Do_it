<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Declaration extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function declaration_comment() {
        return $this->hasMany(Declaration_comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'declaration_tag');
    }

    public function report() {
        return $this->hasOne(Report::class);
    }

    // いいね関連
    public function do_it() {
        return $this->hasMany(Do_it::class);
    }

    public function isDoItBy($user): bool {
        return Do_it::where('user_id', $user->id)->where('Declaration_id', $this->id)->first() !==null;
    }

    public function good_work() {
        return $this->hasMany(Good_work::class);
    }

    public function isGoodWorkBy($user): bool {
        return Good_work::where('user_id', $user->id)->where('Declaration_id', $this->id)->first() !==null;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Declaration_comment extends Model
{
    use HasFactory;

    public function declaration() {
        return $this->belongsTo(Declaration::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}

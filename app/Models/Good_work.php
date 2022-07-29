<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Good_work extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function Declaration() {
        return $this->belongsTo(Declaration::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}

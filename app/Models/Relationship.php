<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['user_id', 'following_user_id'];
    protected $table = 'relationships';
}

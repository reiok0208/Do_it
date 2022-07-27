<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];
    use HasFactory;
    public $timestamps = false;

    public function declarations()
    {
        return $this->belongsToMany(Declaration::class, 'declaration_tag');
    }
}

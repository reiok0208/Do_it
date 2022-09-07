<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Auth;

class Declaration extends Model
{
    use HasFactory;
    use Sortable;

    // テーブル名を明示
    protected $table = 'declarations';

    //可変項目
    protected $fillable =
    [
        'title',
        'start_date',
        'end_date',
        'created_at',
        'do_it_count',
        'good_work_count',
        'follow'
    ];

    //ソートに使うカラムを指定
    public $sortable =
    [
        'title',
        'start_date',
        'end_date',
        'created_at',
        'do_it_count',
        'good_work_count',
        'follow'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // do_itソート
    public function doItSortable($query, $direction)
    {
        return $query->where('end_date','>',date("Y/m/d"))->orderBy('do_it_count', $direction);
    }

    // good_workソート
    public function goodWorkSortable($query, $direction)
    {
        return $query->where('end_date','<',date("Y/m/d"))->orderBy('good_work_count', $direction);
    }

    // followソート
    public function followSortable($query, $direction)
    {
        return $query->whereIn('user_id', Auth::user()->follows()->pluck('user_id'))->orderBy('id', $direction);
    }

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

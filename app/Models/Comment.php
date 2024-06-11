<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['text', 'post_id','auth_id'];

    public static function store($request, $id = null)
    {
        $data = $request->only('text', 'auth_id', 'post_id');
        $data = self::updateOrCreate(['id' => $id], $data);
        return $data;
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function post()
    // {
    //     return $this->belongsTo(Post::class);
    // }
}

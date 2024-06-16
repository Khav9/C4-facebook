<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'tags', 'auth_id', 'images', 'videos'];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
    ];

    public static function store($request, $id = null)
    {
        $data = $request->only('title', 'content', 'tags','auth_id'); 
        $post = self::updateOrCreate(['id' => $id], $data);
        return $post;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }


}

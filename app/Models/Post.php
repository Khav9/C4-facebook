<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'auth_id',
        'tags',
    ];

    public static function store($request, $id = null)
    {
        // Ensure you only get the parameters that match your fillable attributes
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

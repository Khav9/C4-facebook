<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentShowResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::all();
        $comments = CommentShowResource::collection($comments);
        return response()->json(['success' => true, 'data' => $comments, 'message' => 'Comments successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // $post = Post::findOrFail($postId);

        // $comment = new Comment();
        // $comment->content = $request->content;
        // $comment->user_id = Auth::id();
        // $comment->post_id = $post->id;
        // $comment->save();

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

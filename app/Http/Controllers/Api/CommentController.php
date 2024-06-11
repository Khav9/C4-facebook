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
    // public function store(Request $request)
    // {
    //     $user = Auth::user();
    //     $request->validate([
    //         'text' => 'required|string|max:1000',
    //         'auth_id' => $user->id,
    //         'post_id' => $request->id,
    //     ]);
    //     $comment = Comment::create($request);
    //     return response()->json([
    //         'message' => 'Comment added successfully',
    //         'comment' => $comment
    //     ], 201);
    // }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'text' => $request->text,
            'auth_id' => $user->id,
            'post_id' => $request->id,
        ]);

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $comment
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
    public function update(Request $request, string $post_id, string $id)
    {
        $user = Auth::user();
        $comment = Comment::where('post_id', $post_id)->where('id', $id)->first();

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        if ($comment->auth_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $comment->text = $validatedData['text'];
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully',
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $post_id, string $id)
    {
        // Retrieve the comment
        $user = Auth::user();
        // $comment = Comment::find($id);
        $comment = Comment::where('post_id', $post_id)->where('id', $id)->first();

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        if ($comment->auth_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        // // Delete the comment
        $comment->delete();
        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ], 200);
    }

    // app/Http/Controllers/CommentController.php

    // public function destroy($post_id, $comment_id)
    // {
    //     // Retrieve the comment based on post_id and comment_id
    //     $comment = Comment::where('post_id', $post_id)->where('id', $comment_id)->first();

    //     if (!$comment) {
    //         return response()->json(['message' => 'Comment not found'], 404);
    //     }

    //     // Check if the authenticated user is the owner of the comment
    //     if ($comment->user_id !== Auth::id()) {
    //         return response()->json(['message' => 'Unauthorized'], 403);
    //     }

    //     // Delete the comment
    //     $comment->delete();

    //     return response()->json(['message' => 'Comment deleted successfully'], 200);
    // }
}

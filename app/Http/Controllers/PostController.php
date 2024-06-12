<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Resources\PostShowResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $posts = Post::where('auth_id', $user->id)->get(); 
        $posts = PostResource::collection($posts);

        return response()->json(['status' => true, 'data' => $posts]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $user = auth()->user();
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Create the post using the authenticated user's ID
        $post = Post::create([
            'title' => $validatedData['title'],
            'tags' => $request->tags,
            'content' => $request->content,
            'auth_id' => $user->id,
        ]);

        return response()->json([
            "success" => true,
            "message" => "Post created successfully",
            "data" => $post
        ], 201);
    }

    /**
     * Display the specified resource.
     */public function show(string $id)
    {
        $user = Auth::user(); // Get the authenticated user
        
        // Find the post by its ID and ensure it belongs to the authenticated user
        $post = Post::where('auth_id', $user->id)->find($id);

        // Check if the post exists and belongs to the user
        if (!$post) {
            return response()->json(['status' => false, 'message' => 'Post not found or unauthorized'], 404);
        }

        return response()->json(['status' => true, 'data' =>new PostShowResource($post)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post,string $id)
    {
        $user = Auth::user(); // Get the authenticated user
        
        // Find the post by its ID and ensure it belongs to the authenticated user
        $post = Post::where('auth_id', $user->id)->find($id);

        // Check if the post exists and belongs to the user
        if (!$post) {
            return response()->json(['success' => false, 'message' => 'Post not found or unauthorized'], 404);
        }

        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
        ]);

        // Update the post with validated data
        $post->update($validatedData);

        return response()->json(["success" => true, "message" => "Post updated successfully", "data" => $post], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user(); // Get the authenticated user
        
        // Find the post by its ID and ensure it belongs to the authenticated user
        $post = Post::where('auth_id', $user->id)->find($id);

        // Check if the post exists and belongs to the user
        if (!$post) {
            return response()->json(['status' => false, 'message' => 'Post not found or unauthorized'], 404);
        }
        $post->delete();
        return response()->json(['status' => true, 'message'=>'Post delete successfully'], 200);
    }

    public function allPost(){
        $posts = Post::all();
        return response()->json([
            'success' => true,
            'data'  => $posts,
        ]);
    }
    // public function allPost(){
    //     $posts = Post::all();
    //     return response()->json([
    //         'success' => true,
    //         'data'  => PostResource::collection($posts),
    //     ]);
    // }
    
}

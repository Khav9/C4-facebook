<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Resources\PostShowResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'tags' => 'nullable|string',
            'content' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'videos.*' => 'mimes:mp4,mov,ogg,qt|max:20000' 
        ]);

        $imagePaths = null;
        $videoPaths = null;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('post/images', 'public');
                $imagePaths[] = Storage::url($path);
            }
        }

        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $path = $video->store('post/videos', 'public');
                $videoPaths[] = Storage::url($path);
            }
        }
        $post = Post::create([
            'title' => $validatedData['title'],
            'tags' => $request->tags,
            'content' => $request->content,
            'auth_id' => $user->id,
            'images' => json_encode($imagePaths),
            'videos' => json_encode($videoPaths),
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
        $user = Auth::user();
    
        $post = Post::where('auth_id', $user->id)->find($id);

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
        $user = Auth::user();
        
        $post = Post::where('auth_id', $user->id)->find($id);

        if (!$post) {
            return response()->json(['success' => false, 'message' => 'Post not found or unauthorized'], 404);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
        ]);

        $post->update($validatedData);
        return response()->json(["success" => true, "message" => "Post updated successfully", "data" => $post], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user(); 
        
        $post = Post::where('auth_id', $user->id)->find($id);

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
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Auth;
use Illuminate\Http\Request;
use Validator;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'post_id' => 'required',
        ]);
    
        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
    
        // Get the validated data
        $data = $validator->validated();
    
        // Get the authenticated user
        $user = Auth::user();
        // Debugging dump (this can be removed in production)
        // dd($user);
    
        // Check if the like already exists
        $like = Like::where('auth_id', $user->id)
            ->where('post_id', $data['post_id'])
            ->first();
    
        if ($like) {
            // If the like exists, delete it (unlike)
            $like->delete();
            return response()->json([
                'message' => 'You unliked a post',
            ], 200);
        } else {
            // If the like does not exist, create a new like
            $like = new Like();
            $like->auth_id = $user->id;
            $like->post_id = $data['post_id'];
            
            if ($like->save()) {
                return response()->json([
                   'message' => 'You liked a post',
                ], 201);
            } else {
                return response()->json([
                   'message' => 'Something went wrong',
                ], 500);
            }
        }
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

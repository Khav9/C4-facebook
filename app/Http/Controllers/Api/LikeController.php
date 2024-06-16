<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $validator = Validator::make($request->all(), [
            'post_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
        $data = $validator->validated();
        $user = Auth::user();
        $like = Like::where('user_id', $user->id)
            ->where('post_id', $data['post_id'])
            ->first();
    
        if ($like) {
            $like->delete();
            return response()->json([
                'message' => 'You unliked a post',
            ], 200);
        } else {
            $like = new Like();
            $like->user_id = $user->id;
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

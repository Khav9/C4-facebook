<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = auth()->user(); 
        $followers = Follow::where('user_id', $user->id)->get();

        return response()->json([
            'status' => true,
            'following_count' => $followers->count(),
            'data' => $followers,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', 
        ]);

        $user = $request->user();
    if ($user->id === (int) $request->user_id) {
        return response()->json(['message' => 'You cannot follow yourself.'], 400);
    }

        $follower = Follow::where('user_id', $request->user_id)
            ->where('following_id', $user->id)
            ->first();

        if (!$follower) {
            $follower = new Follow();
            $follower->user_id = $user->id;

            if ($follower->save()) {
                return response()->json([
                    'message' => 'You are following this user',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Something went wrong following this user, please try again',
                ], 500);
            }
        } else {
            if ($follower->delete()) {
                return response()->json([
                    'message' => 'You unfollowed this user',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Something went wrong, please try again',
                ], 500);
            }
        }
    }
    public function show(string $id)
    {
        // Implement logic to show a specific follow relationship if needed
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Implement logic to update a follow relationship if needed
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Implement logic to delete a follow relationship if needed
    }
}

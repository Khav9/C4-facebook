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
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'following_id' => 'required|exists:users,id', 
    //     ]);

    //     $user = $request->user();
    // if ($user->id === (int) $request->following_id) {
    //     return response()->json(['message' => 'You cannot follow yourself.'], 400);
    // }

    //     $follower = Follow::where('user_id', $request->following_id)
    //         ->where('following_id', $user->id)
    //         ->first();

    //     if (!$follower) {
    //         $follower = new Follow();
    //         $follower->user_id = $user->id;
    //         $follower->following_id = $request->following_id;

    //         if ($follower->save()) {
    //             return response()->json([
    //                 'message' => 'You are following this user',
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'message' => 'Something went wrong following this user, please try again',
    //             ], 500);
    //         }
    //     } else {
    //         if ($follower->delete()) {
    //             return response()->json([
    //                 'message' => 'You unfollowed this user',
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'message' => 'Something went wrong, please try again',
    //             ], 500);
    //         }
    //     }
    // }

    public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'following_id' => 'required|exists:users,id', 
    ]);

    $user = $request->user();
    $followingId = (int) $request->following_id;

    // Check if the user is trying to follow themselves
    if ($user->id === $followingId) {
        return response()->json(['message' => 'You cannot follow yourself.'], 400);
    }

    // Check if the follow relationship already exists
    $follow = Follow::where('user_id', $user->id)
        ->where('following_id', $followingId)
        ->first();

    // If the relationship does not exist, create it
    if (!$follow) {
        $follow = new Follow();
        $follow->user_id = $user->id;
        $follow->following_id = $followingId;

        if ($follow->save()) {
            return response()->json([
                'message' => 'You are now following this user',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Something went wrong while following this user, please try again',
            ], 500);
        }
    } else {
        // If the relationship exists, delete it to unfollow
        if ($follow->delete()) {
            return response()->json([
                'message' => 'You have unfollowed this user',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Something went wrong while unfollowing this user, please try again',
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

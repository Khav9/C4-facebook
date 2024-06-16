<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendshipController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $friends = Friendship::where('user_id', $user->id)->get();
        return response()->json(['status' => true, 'data' => $friends]);
    }
    public function sendRequest($id)
    {
        $user = Auth::user();
        $friend = User::findOrFail($id);

        if ($user->id === $friend->id) {
            return response()->json(['message' => 'You cannot send a friend request to yourself.'], 400);
        }

        $existingRequest = $user->sentRequests()->where('user_id', $user->id)->first();
        if ($existingRequest) {
            return response()->json(['message' => 'Friend request already sent.'], 400);
        }

        $user->sentRequests()->attach($friend);

        return response()->json(['message' => 'Friend request sent.'], 200);
    }

    public function acceptRequest($id)
    {
        $user = Auth::user();
        $friend = User::findOrFail($id);

        $friendRequest = $user->friendRequests()->where('user_id', $friend->id)->wherePivot('status', 'pending')->first();
        if (!$friendRequest) {
            return response()->json(['message' => 'No pending friend request found.'], 404);
        }

        $user->friendRequests()->updateExistingPivot($friend->id, ['status' => 'accepted']);
                
        $user->friends()->attach($friend->id);
        $user->sentRequests()->updateExistingPivot($friend->id, ['status' => 'accepted']);
        return response()->json(['message' => 'Friend request accepted.'], 200);
    }

    public function rejectRequest($id)
    {

        $user = Auth::user();
        $friend = User::findOrFail($id);

        $friendRequest = $user->friendRequests()->where('user_id', $friend->id)->wherePivot('status', 'pending')->first();
        if (!$friendRequest) {
            return response()->json(['message' => 'No pending friend request found.'], 404);
        }

        $user->friendRequests()->detach($friend);

        return response()->json(['message' => 'Friend request rejected.'], 200);
    }
}

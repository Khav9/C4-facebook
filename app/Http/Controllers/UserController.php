<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\usershowresource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class UserController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'message'       => 'Login success',
            'data'  => $request->user(),
        ]);
    }

    public function index()
    {
        $users = User::all();
        return response()->json([
            'success' => true,
            'message'       => 'Login success',
            'data'  => $users,
        ]);
    }

    public function show()
    {
        $user = Auth::user();
        $user = new usershowresource($user);
        return response()->json([
            'success' => true,
            'message' => true,
            'data'  => $user,
        ]);
    }

    public function update(UserRequest $request)
    {
        $user = Auth::user();
        $data = $request->all();

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user,
        ], 200);
    }
    public function uploadProfile(Request $request){
        $user = Auth::user();
        $oldPhoto = $user->profile_image;

         if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $path = $image->store('profile_images', 'public');
            $path = Storage::url($path);

            $user->profile_image = $path;
        }

        if ($user->save()){
            if ($oldPhoto != $user->profile_image){
                Storage::delete($oldPhoto);
            }
            return response()->json($user,200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Please try again !'
            ],500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

}

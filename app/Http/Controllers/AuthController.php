<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'User not found'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $permissions = $user->getAllPermissions();
        $roles = $user->getRoleNames();
        return response()->json([
            'message' => 'Login success',
            'data' => $user,
        ]);
    }


    public function register(Request $request)
    {
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $path = $image->store('profile_images', 'public');
            $path = Storage::url($path);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_image' => isset($path) ? $path : null,
        ]);
        $user->syncRoles(["User"]);
        $user->syncPermissions(["view_users", "view_roles", "view_permissions"]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }
    public function logout()
    {
        try {
            $user = Auth::user();
            if ($user) {
                $user->tokens()->delete();
                return response()->json([
                    'message' => 'Logout successfully'
                ]);
            } else {
                return response()->json([
                    'message' => 'No authenticated user'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while logging out',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

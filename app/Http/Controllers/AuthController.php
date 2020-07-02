<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return response([
                    'status_code' => 422,
                    'message' => 'Email or password error'
                ], 422);
            }
            $user = User::all()->where('email', $request->email)->first();

            $user->tokens()->where('tokenable_id', $user->id)->delete();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in Login');
            }
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response([
                'status_code' => 200,
                'access_token' => $tokenResult,
                'state' => 'connected',
                'token_type' => 'Bearer',
            ], 200);
        } catch (Exception $error) {
            return response([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'email|required',
                'password' => 'required|min:8'
            ]);
            $user = User::all()->where('email', $request->email)->first();
            if ($user) {
                return response([
                    'status_code' => 422,
                    'message' => 'Email already exists',
                ], 422);
            }
            $user = new User;
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user = $user->save();

            $user = User::all()->where('email', $request->email)->first();
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response([
                'status_code' => 200,
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
            ], 200);
        } catch (Exception $error) {
            return response([
                'status_code' => 500,
                'message' => 'Error in Register',
                'error' => $error,
            ], 500);
        }
    }
    public function logout()
    {
        Auth::logout();
        return response(['message' => 'Logged Out'], 200);
    }

    public function test()
    {
        return response([
            'status_code' => 200,
            'message' => 'success',
        ], 200);
    }
}

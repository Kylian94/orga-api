<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return response([
            "status_code" => 200,
            "data" => $user
        ], 200);
    }

    public function edit_account(Request $request)
    {
        try {
            $user = Auth::user();
            $result = $user->update($request->all());
            if ($result) {
                return response([
                    'message' => "user updated",
                    'status_code' => 200
                ], 200);
            } else {
                return response([
                    'message' => "user not updated",
                    'status_code' => 400
                ], 400);
            }
        } catch (Exception $error) {
            return response([
                'status_code' => 500,
                'message' => 'Cannot edit account',
                'error' => $error,
            ], 500);
        }
    }

    public function logout()
    {
        try {
            $user = Auth::user();
            $result = $user->tokens()->where('tokenable_id', $user->id)->delete();
            if (!$result) {
                return response([
                    "message" => "Error with delete",
                    "status_code" => 400
                ], 400);
            } else {
                return response([
                    "message" => "Disconnected ",
                    "status_code" => 200
                ], 200);
            }
        } catch (Exception $error) {
            return response([
                'status_code' => 500,
                'message' => 'Cannot delete user',
                'error' => $error,
            ], 500);
        }
    }



    public function destroy()
    {
        try {
            $user = Auth::user();
            $user->tokens()->where('tokenable_id', $user->id)->delete();
            $result = $user->delete();

            if (!$result) {
                return response([
                    "message" => "Error with delete",
                    "status_code" => 400
                ], 400);
            } else {
                return response([
                    "message" => "Account deleted",
                    "status_code" => 200
                ], 200);
            }
        } catch (Exception $error) {
            return response([
                'status_code' => 500,
                'message' => 'Cannot delete user',
                'error' => $error,
            ], 500);
        }
    }
}

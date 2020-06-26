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

        return response()->json([
            "status_code" => 200,
            "data" => $user
        ]);
    }

    public function edit_account(Request $request)
    {
        try {
            $user = Auth::user();
            $result = $user->update($request->all());
            if ($result) {
                return response()->json([
                    'message' => "user updated",
                    'status_code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => "user not updated",
                    'status_code' => 400
                ]);
            }
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Cannot edit account',
                'error' => $error,
            ]);
        }
    }

    public function logout()
    {

        try {
            $user = Auth::user();
            $result = $user->tokens()->where('tokenable_id', $user->id)->delete();


            if (!$result) {
                return response()->json([
                    "message" => "Error with delete",
                    "status_code" => 400
                ]);
            } else {
                return response()->json([
                    "message" => "Disconnected ",
                    "status_code" => 200
                ]);
            }
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Cannot delete user',
                'error' => $error,
            ]);
        }
    }



    public function destroy()
    {

        try {
            $user = Auth::user();
            $user->tokens()->where('tokenable_id', $user->id)->delete();
            $result = $user->delete();

            if (!$result) {
                return response()->json([
                    "message" => "Error with delete",
                    "status_code" => 400
                ]);
            } else {
                return response()->json([
                    "message" => "Account deleted",
                    "status_code" => 200
                ]);
            }
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Cannot delete user',
                'error' => $error,
            ]);
        }
    }
}

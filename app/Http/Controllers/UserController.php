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
        $user = Auth::user();
        if ($request->firstname) {
            $user->update([
                "firstname" => $request->firstname,
            ]);
            return response()->json([
                'message' => "user updated",
                'status_code' => 200
            ]);
        }
        if ($request->lastname) {
            $user->update([
                "lastname" => $request->lastname,
            ]);
            return response()->json([
                'message' => "user updated",
                'status_code' => 200
            ]);
        }
        if ($request->email) {
            $user->update([
                "email" => $request->email,
            ]);
            return response()->json([
                'message' => "user updated",
                'status_code' => 200
            ]);
        }
        if ($request->password) {
            $user->update([
                "password" => Hash::make($request->password),
            ]);
            return response()->json([
                'message' => "user updated",
                'status_code' => 200
            ]);
        }
        return response()->json([
            'message' => "no update found",
            'status_code' => 200
        ]);
    }

    public function destroy()
    {

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
    }
}

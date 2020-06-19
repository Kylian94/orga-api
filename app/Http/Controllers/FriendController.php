<?php

namespace App\Http\Controllers;

use App\Friend;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($user_id)
    {
        try {
            $userSearch = User::find($user_id);

            if (!$userSearch) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'error user not founded'
                ]);
            } else {
                $friend = new Friend;
                $friend->user_id = Auth::user()->id;
                $friend->friend_id = $user_id;
                $friend->is_accepted = 0;
                $friend->save();
                Auth::user()->friends;

                return response()->json([
                    'status_code' => 200,
                    'user' => Auth::user(),
                    'message' => 'success'
                ]);
            }
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error add friend',
                'error' => $error,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Friend  $friend
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try {

            $friends = collect([Auth::user()->friendOfAccepted, Auth::user()->friendsOfMineAccepted])->collapse()->all();
            return response()->json([
                'status_code' => 200,
                'user' => Auth::user(),
                'friends' => $friends,
                'message' => 'success'
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error found friends',
                'error' => $error,
            ]);
        }
    }
    public function pending(Friend $friend)
    {
        try {
            $friends = Auth::user()->friendsOfMine;
            return response()->json([
                'status_code' => 200,
                'user' => Auth::user(),
                'message' => 'success'
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error found friends',
                'error' => $error,
            ]);
        }
    }
    public function request(Friend $friend)
    {
        try {
            $friends = Auth::user()->friendOf;
            return response()->json([
                'status_code' => 200,
                'user' => Auth::user(),
                'message' => 'success'
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error found friends',
                'error' => $error,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Friend  $friend
     * @return \Illuminate\Http\Response
     */
    public function edit(Friend $friend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Friend  $friend
     * @return \Illuminate\Http\Response
     */
    public function accept_friend($user_id)
    {
        try {

            $result = Friend::where('user_id', $user_id)->first();

            if ($result) {
                Friend::where('friend_id', Auth::user()->id)->where('user_id', $user_id)->update(['is_accepted' => 1]);
                return response()->json([
                    'status_code' => 200,
                    'user' => Auth::user(),
                    'message' => 'friend accepted'
                ]);
            } else {
                return response()->json([
                    'status_code' => 404,
                    'user' => Auth::user(),
                    'message' => 'friend not found'
                ]);
            }
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error found friends',
                'error' => $error,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Friend  $friend
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id)
    {
        try {
            $result = Friend::where('friend_id', $user_id)->first();
            $second_result = Friend::where('user_id', $user_id)->first();

            if ($result || $second_result) {
                Friend::where('friend_id', $user_id)->where('user_id', Auth::user()->id)->delete();
                Friend::where('friend_id', Auth::user()->id)->where('user_id', $user_id)->delete();

                return response()->json([
                    'status_code' => 200,
                    'user' => Auth::user(),
                    'message' => 'success friend delete'
                ]);
            }
            return response()->json([
                'status_code' => 404,
                'message' => 'Error friend not found',

            ]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error delete friends',
                'error' => $error,
            ]);
        }
    }
}

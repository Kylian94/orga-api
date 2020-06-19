<?php

namespace App\Http\Controllers;

use App\Event;
use App\Member;
use App\User;
use Illuminate\Http\Request;

class MemberController extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $event = Event::find($id);
            if ($event) {
                $members_accepted = $event->users_accepted;
                $members_pending = $event->users_pending;

                foreach ($members_pending as $member_pending) {
                    $member_pending->user;
                }

                foreach ($members_accepted as $member_accepted) {
                    $member_accepted->user;
                }

                return response()->json([
                    'status_code' => 200,
                    'members_valid' => $members_accepted ?? "",
                    'members_wait' => $members_pending ?? "",
                    'message' => 'success'
                ]);
            } else {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Error event not found',
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        //
    }
}

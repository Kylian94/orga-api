<?php

namespace App\Http\Controllers;

use App\Event;
use App\Liste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { }

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
    public function store(Request $request, $event_id)
    {
        try {
            $request->validate([
                'title' => 'required',
            ]);
            $liste = new Liste;
            $liste->title = $request->title;
            $liste->event_id = $event_id;
            $liste->save();

            return response([
                'status_code' => 200,
                'event' => Event::find($event_id),
                'liste' => $liste,
                'message' => 'success'
            ], 200);
        } catch (Exception $error) {
            return response([
                'status_code' => 500,
                'message' => 'Error create liste',
                'error' => $error,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Liste  $liste
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $liste = Liste::find($id);
            $items = $liste->items;
            foreach ($items as $item) {
                $users =  $item->users;
            }
            return response([
                'status_code' => 200,
                'message' => 'success',
                'liste' => $liste,
            ], 200);
        } catch (Exception $error) {
            return response([
                'status_code' => 500,
                'message' => 'Error create liste',
                'error' => $error,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Liste  $liste
     * @return \Illuminate\Http\Response
     */
    public function edit_liste(Request $request, $id)
    {
        try {
            $liste = Liste::find($id);
            $user = Auth::user();
            $event = $liste->event;
            if ($user->id == $event->user_id) {
                $liste->update($request->all());
                return response([
                    'status_code' => 200,
                    'message' => 'success',
                    'liste' => $liste,
                ], 200);
            } else {
                return response([
                    'status_code' => 422,
                    'message' => 'not autorized',
                ], 422);
            }
        } catch (Exception $error) {
            return response([
                'status_code' => 500,
                'message' => 'Error create liste',
                'error' => $error,
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Liste  $liste
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Liste $liste)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Liste  $liste
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $liste = Liste::find($id);
        $user = Auth::user();
        $event = $liste->event;
        if ($user->id == $event->user_id) {
            $result = $liste->delete();
            if ($result) {
                return response([
                    'status_code' => 200,
                    'message' => 'success',
                    'liste' => $liste,
                ], 200);
            } else {
                return response([
                    'status_code' => 500,
                    'message' => 'error delete',
                ], 500);
            }
        } else {
            return response([
                'status_code' => 422,
                'message' => 'not autorized',
            ], 422);
        }
    }
}

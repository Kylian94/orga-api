<?php

namespace App\Http\Controllers;

use App\Event;
use App\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        if (Auth::user()) {
            $events = Auth::user()->events_member;
            if ($events) {

                foreach ($events as $event) {
                    $event->setAttribute('nb_members', count($event->users_accepted));
                }
                //$events = Event::all();
                return response([
                    'status_code' => 200,
                    'message' => 'Your Event list',
                    'events' => $events,
                ], 200);
            }
        } else {
            return response([
                'status_code' => 403,
                'message' => 'not connected',
            ], 403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'date' => 'required',
                'adresse' => 'required',
                'zipCode' => 'required',
                'city' => 'required'
            ]);

            $event = new Event;
            $event->title = $request->title;
            $event->isPrivate = $request->isPrivate;
            $event->date = $request->date;
            $event->adresse = $request->adresse;
            $event->zipCode = $request->zipCode;
            $event->city = $request->city;
            $event->user_id = Auth::user()->id;
            $event->save();
            $user = Auth::user();
            $user->events_member()->attach($event->id, array('is_accepted' => 1));

            $lastInsert = Event::find($event->id);

            if ($request->members) {
                $members = array_keys($request->members);
                dd($members);
                foreach ($members as $member) {
                    DB::table("event_user")->insert([
                        ['user_id' => $member],
                        ['event_id' => $lastInsert->id],

                    ]);
                }
            }

            return response([
                'status_code' => 200,
                'event' => $event
            ], 200);
        } catch (Exception $error) {
            return response([
                'status_code' => 500,
                'message' => 'Error create event',
                'error' => $error,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $event = Event::find($id);
            $user = $event->user;

            foreach ($event->users_accepted as $user) {
                $user->user;
            }
            foreach ($event->users_pending as $user) {
                $user->user;
            }

            $listes = $event->listes;

            foreach ($listes as $liste) {
                $items = $liste->items;
            }
            return response([
                'status_code' => 200,
                'message' => 'Your list',
                'event' => $event,
            ], 200);
        } catch (Exception $error) {
            return response([
                'status_code' => 500,
                'message' => 'Error view event',
                'error' => $error,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit_item($id)
    {
        $item = Item::find($id);
        $user = Auth::user();
        $result = DB::table("item_user")->insert([
            [
                'user_id' => $user->id,
                'item_id' => $id
            ]

        ]);
        if ($result) {
            return response([
                'item' => $item,
            ]);
        } else {
            return response([
                'status_code' => 500,
                'message' => 'Error create liste',

            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $event = Event::find($id);

            if ($event->user_id == Auth::user()->id) {

                $listes = $event->listes;

                foreach ($listes as $liste) {
                    //dd($liste->items);
                    $items = $liste->items;
                    foreach ($items as $item) {
                        $item->delete();
                        $item->users()->detach();
                    }
                    $liste->delete();
                }
                $event->delete();

                return response([
                    'message' => "event deleted",
                    'status_code' => 200
                ], 200);
            } else {
                return response([
                    'message' => "not autorized",
                    'status_code' => 422
                ], 422);
            }
        } catch (Exception $error) {
            return response([
                'status_code' => 500,
                'message' => 'Cannot delete event',
                'error' => $error,
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Event;
use App\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $events = Auth::user()->events_member;

        foreach ($events as $event) {
            $event->setAttribute('nb_members', count($event->users_accepted));
        }

        return response()->json([
            'status_code' => 200,
            'message' => 'Your Event list',
            'events' => $events,
        ]);
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

            return response()->json([
                'status_code' => 200,
                'event' => $event
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error create event',
                'error' => $error,
            ]);
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
            return response()->json([
                'status_code' => 200,
                'message' => 'Your list',
                'event' => $event,
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error view event',
                'error' => $error,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit_event(Request $request, $id)
    {
        try {
            $event = Event::find($id);

            if ($event->user_id == Auth::user()->id) {
                $result = $event->update($request->all());
                if ($result) {
                    return response()->json([
                        'message' => "event updated",
                        'status_code' => 200
                    ]);
                } else {
                    return response()->json([
                        'message' => "event not updated",
                        'status_code' => 400
                    ]);
                }
            } else {
                return response()->json([
                    'message' => "not autorized",
                    'status_code' => 422
                ]);
            }
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Cannot edit event',
                'error' => $error,
            ]);
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

                return response()->json([
                    'message' => "event deleted",
                    'status_code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => "not autorized",
                    'status_code' => 422
                ]);
            }
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Cannot delete event',
                'error' => $error,
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Item;
use App\Liste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
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
    public function store(Request $request, $liste_id)
    {
        try {
            $request->validate([
                'title' => 'required',
            ]);
            $item = new Item;
            $item->title = $request->title;
            $item->liste_id = $liste_id;
            $item->author_id = Auth::user()->id;
            $item->save();
            $user = Auth::user();
            $user->items()->attach($item->id);
            return response([
                'status_code' => 200,
                'liste' => Liste::find($liste_id),
                'item' => $item,
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
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::find($id);
        $user = Auth::user();
        $item->users()->detach();
        if ($user->id == $item->author_id) {
            $result = $item->delete();
            if ($result) {
                return response([
                    'status_code' => 200,
                    'message' => 'success delete',
                    'item' => $item,
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

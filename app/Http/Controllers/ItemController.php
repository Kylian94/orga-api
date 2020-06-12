<?php

namespace App\Http\Controllers;

use App\Item;
use App\Liste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required',
            ]);

            $item = new Item;
            $item->title = $request->title;
            $item->liste_id = $id;
            $item->author_id = Auth::user()->id;
            $item->save();
            $user = Auth::user();
            $user->items()->attach($item->id);

            return response()->json([
                'status_code' => 200,
                'liste' => Liste::find($id),
                'item' => $item,
                'message' => 'success'
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error create liste',
                'error' => $error,
            ]);
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
    public function edit(Item $item)
    {
        //
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
                return response()->json([
                    'status_code' => 200,
                    'message' => 'success delete',
                    'item' => $item,
                ]);
            } else {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'error delete',
                ]);
            }
        } else {
            return response()->json([
                'status_code' => 422,
                'message' => 'not autorized',
            ]);
        }
    }
}

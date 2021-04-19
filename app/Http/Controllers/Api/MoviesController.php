<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movies;
use Illuminate\Support\Facades\Auth;

class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movies = auth()->user()->movies;
        return response()->json([
            'success' => true,
            'data' => $movies
        ]);
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
        $this->validate($request, [
            'name' => 'required',
            'year' => 'required'
        ]);

        $movie = new Movies();
        $movie->name = $request->name;
        $movie->year = $request->year;

        if(auth()->user()->movies()->save($movie)){
            return response()->json([
                'success' => true,
                'data' => $movie->toArray()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Movie not added'
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $movie = Auth::user()->movies()->find($id);
        if(!$movie){
            return response()->json([
                'success'=>false,
                'message' => 'Movie not found'
            ],400);
        } else {
            return response()->json([
                'success' => true,
                'data' => $movie->toArray()
            ],400);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $movie = Auth::user()->movies()->find($id);

        if(!$movie){
            return response()->json([
                'success' => false,
                'message' => 'Movie not found'
            ],400);
        }

        $updated = $movie->fill($request->all())->save();
        
        if($updated){
            return response()-> json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Movie can not be updated'
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $movie = Auth::user()->movies()->find($id);

        if(!$movie){
            return response()->json([
                'success' => false,
                'message' => 'Movie not found'
            ], 400);
        }

        if($movie->delete()){
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Movie can not be deleted'
            ], 500);
        }
    }
}

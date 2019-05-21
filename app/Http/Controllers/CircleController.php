<?php

namespace App\Http\Controllers;

use App\Circle;
use App\Http\Resources\CircleResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\TagsResource;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CircleController extends Controller
{

    public function configure() {
        return view('circles');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return TagsResource
     */
    public function index(Request $request)
    {
        return new CircleResource(Tag::all());
    }

    public function users($id) {
        $circle = Circle::findOrFail($id);
        return $circle->users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Exception
     */
    public function store(Request $request)
    {
        if(auth()->user()->id == env('PUBLIC_USER_ID', 2)) {
            throw new \Exception('Public user cannot post.');
        }

        $user = Auth::user();
        $circle = new Circle();
        $circle->name = $request->name;
        $circle->user_id = $user->id;
        $circle->save();
        $user->circles()->attach($circle);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return CircleResource
     */
    public function show($id)
    {
        return new CircleResource(Circle::find($id));
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

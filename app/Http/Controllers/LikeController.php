<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Resources\LikeResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\TagsResource;
use App\Like;
use App\Tag;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
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
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function store(Request $request)
    {
        // Public user check
        if(auth()->user()->id == env('PUBLIC_USER_ID', 2)) {
            throw new Exception('Public user cannot post.');
        }

        // Values missing
        if($request->answer_id == null) {
            throw new Exception('No answer id specified in JSON body (answer_id).');
        }

        // Already liked check
        $alreadyLiked = Like::where('user_id', '=', auth()->user()->id)
            ->where('answer_id', '=', $request->answer_id)
            ->get()->count() > 0;

        if($alreadyLiked) {
            throw new Exception('User already liked this answer.');
        }

        // Process like
        $like = new Like();
        $like->user_id = auth()->user()->id;
        $like->answer_id = $request->answer_id; // todo: verify the permissions

        // Save the model
        $like->save();

        // Return the id
        return ['id' => $like->id, 'state' => 'Answer liked.'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return LikeResource
     */
    public function show($id)
    {
        return new LikeResource(Like::find($id));
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
     * @param Request $request
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
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function destroy($id)
    {
        $like = Like::where('user_id', '=', Auth::user()->id)
            ->where('answer_id', '=', $id)
            ->firstOrFail();


        // Check the user
        if($like->user != Auth::user()) {
            throw new Exception('You do not have permission to delete this like.');
        }

        $like->delete();
        return ['state' => 'Like removed.'];
    }
}

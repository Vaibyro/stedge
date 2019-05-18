<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostsResource;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return new PostsResource(Post::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function store(Request $request) {
        $post = new Post();

        // Content of the message
        $post->content = $request->message;

        // User id
        $post->user_id = Auth::id();

        // Post creation state
        $post->state_id = env('DEFAULT_STATE_ID', 1);

        // circle
        if (isset($request->circle)) {
            $post->circle_id = $request->circle;
        } else {
            $post->circle_id = env('PUBLIC_CIRCLE_ID', 1);
        }

        // Post default emergency
        $post->emergency = env('DEFAULT_EMERGENCY', 1);

        // Save the model
        $post->save();

        // tags
        if (isset($request->tags)) {
            foreach ($request->tags as $tag) {
                $tag = Tag::find($tag);
                $tag->posts()->attach($post);
            }
        }

        // Return the id
        return ['id' => $post->id];
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return new PostResource(Post::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}

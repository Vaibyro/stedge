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
    public function view($id) {
        // Check is exists.
        $post = Post::find($id);
        if($post == null) {
            abort(404);
        }

        // Return the view
        return view('post', ['id' => $id]);
    }

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
     * Approbe an answer
     * @param $postId
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function approbe($postId, Request $request) {
        // Public user check
        if(auth()->user()->id == env('PUBLIC_USER_ID', 2)) {
            throw new \Exception('Public user cannot post.');
        }

        $answerId = $request->answer_id;
        $post = Post::findOrFail($postId);
        $answer = Answer::findOrFail($answerId);
        // check
        if($post->user != Auth::user()) {
            throw new \Exception("Cannot make approbation for others posts");
        }

        if($answer->post->id != $post->id) {
            throw new \Exception("This answer is not from this post");
        }

        $post->answer_id = $answerId;
        $post->state_id = 2; // change state id
        $post->save();

        return ['answer_id' => $answerId, 'state' => 'Answer approbated'];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function store(Request $request) {
        // Public user check
        if(auth()->user()->id == env('PUBLIC_USER_ID', 2)) {
            throw new \Exception('Public user cannot post.');
        }

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

        // new tags
        $newTags = [];
        if (isset($request->new_tags)) {
            foreach ($request->new_tags as $newTagName) {
                $newTag = new Tag();
                $newTag->name = $newTagName;
                $newTag->save();
                $newTag->posts()->attach($post); // attach the post
                array_push($newTags, ['id' => $post->id, 'name' => $newTagName]);
            }
        }

        // Return the id
        return [
            'id' => $post->id,
            'state' => 'Posted',
            'new_tags' => $newTags
        ];
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
     * @return array
     * @throws \Exception
     */
    public function destroy($id) {
        if(auth()->user()->id == env('PUBLIC_USER_ID', 2)) {
            throw new \Exception('Public user cannot remove.');
        }

        $post = Post::find($id);
        if($post->user_id == Auth::user()->id)
        {
            Post::destroy($id);
            return [
                'id' => $id,
                'state' => 'Post deleted'
            ];
        }

        throw new \Exception('Cannot delete others posts.');

    }
}

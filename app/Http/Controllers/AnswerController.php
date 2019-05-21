<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Resources\AnswerResource;
use App\Http\Resources\TagResource;
use App\Tag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return "test" . Auth::id();
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
     * @throws Exception
     */
    public function store(Request $request) {
        // Public user check
        if(auth()->user()->id == env('PUBLIC_USER_ID', 2)) {
            throw new Exception('Public user cannot post.');
        }

        $answer = new Answer();

        $answer->content = $request->message;
        $answer->post_id = $request->post_id;
        $answer->user_id = Auth::id();
        $answer->save();

        // return the id
        return ['id' => $answer->id];
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Answer $answer
     * @return AnswerResource
     */
    public function show(Answer $answer) {
        return new AnswerResource($answer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Answer $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Answer $answer) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Answer $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Answer $answer
     * @return array
     * @throws Exception
     */
    public function destroy(Answer $answer) {
        if(auth()->user()->id == env('PUBLIC_USER_ID', 2)) {
            throw new Exception('Public user cannot remove.');
        }

        if($answer->user != Auth::user()) {
            throw new Exception('Cannot delete others answers.');
        }

        if($answer->post->state->id != env('DEFAULT_STATE_ID', 1)) {
            throw new Exception('Thread is not open, cannot delete answer.');
        }

        $answer->delete();

        return [
            'state' => 'Answer deleted'
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Resources\AnswerResource;
use App\Http\Resources\FeedResource;
use App\Http\Resources\TagResource;
use App\Tag;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeedController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return array
     * @throws \Exception
     */
    public function index(Request $request) {
        $posts = DB::table('posts')
            ->select('posts.id', 'posts.created_at', 'posts.emergency', DB::raw('count(answers.post_id) as answers_count'))
            ->join('circle_user', function ($join) {
                $join->on('posts.circle_id', '=', 'circle_user.circle_id')->orOn('posts.circle_id', '=', DB::raw(env('PUBLIC_CIRCLE_ID', 1)));
            })
            ->leftJoin('answers', 'answers.post_id', '=', 'posts.id')
            ->leftJoin('post_tag', 'post_tag.post_id', '=', 'posts.id')

            ->where(function ($query) {
                $query->where('circle_user.user_id', '=', Auth::user()->id)

                    // But allow public circle
                    ->orWhere('posts.circle_id', '=', DB::raw(env('PUBLIC_CIRCLE_ID', 1)));
            })

            // Restrict to circles

            ->orderBy('emergency')
            ->groupBy('posts.id', 'posts.created_at', 'posts.emergency');

        // Filters
        if(isset($request->circles)) {
            $circles = explode(",", $request->circles);
            $posts = $posts->whereIn('posts.circle_id', $circles);
        }

        if(isset($request->tags)) {
            $tags = explode(",", $request->tags);
            $posts = $posts->whereIn('post_tag.tag_id', $tags);
        }

        // Limit the amount
        $posts = $posts->limit(1000);

        // Execute the query
        $posts = $posts->get();

        foreach ($posts as $post) {
            // calculate the coeff
            $post->coeff = $this->calculateCoeff($post);
        }
        $posts = $posts->sortByDesc('coeff');


        return ['data' => [
            'posts' => $posts->values()->all(),
        ]];
    }


    /**
     * Display the specified resource.
     *
     * @param $id
     * @return void
     */
    public function show($id) {
        $post = DB::table('posts')
            ->select('posts.id', 'posts.created_at', 'posts.emergency', DB::raw('count(answers.post_id) as answers_count'))
            ->join('circle_user', function ($join) {
                $join->on('posts.circle_id', '=', 'circle_user.circle_id')->orOn('posts.circle_id', '=', DB::raw(1));
            })
            ->leftJoin('answers', 'answers.post_id', '=', 'posts.id')
            ->orderBy('emergency')
            ->groupBy('posts.id', 'posts.created_at', 'posts.emergency')
            ->where('posts.id', '=', $id)
            ->get();
        $post = $post->first();

        // calculate the coeff
        // todo: ameliorer answer count coeff
        $post->coeff =$this->calculateCoeff($post);

        return [
            'data' => $post,
        ];
    }

    private function calculateCoeff($post) {
        $date = new DateTime($post->created_at);
        $now = new DateTime();
        $diff = $date->diff($now);
        $hours = $diff->days * 24;
        $hours += $diff->h;
        return round(($hours + 1) * $post->emergency * (10 / ($post->answers_count + 1)));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request) {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Answer $answer
     * @return \Illuminate\Http\Response
     */
    public function edit() {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
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
     * @return \Illuminate\Http\Response
     */
    public function destroy() {
        //
    }
}

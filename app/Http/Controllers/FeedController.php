<?php

namespace App\Http\Controllers;

use App\Answer;
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
            ->select('posts.id', 'posts.created_at', 'posts.emergency', 'posts.state_id', DB::raw('count(answers.post_id) as answers_count'))
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

            ->whereNull('posts.deleted_at')
            ->groupBy('posts.id', 'posts.created_at', 'posts.emergency', 'posts.state_id');

        // Filters
        if(isset($request->circles)) {
            $circles = explode(",", $request->circles);
            $posts = $posts->whereIn('posts.circle_id', $circles);
        }

        // Tags
        if(isset($request->tags)) {
            $tags = explode(",", $request->tags);
            $posts = $posts->whereIn('post_tag.tag_id', $tags);
        }

        // Stage filter
        if(isset($request->state)) {
            $posts = $posts->where('posts.state_id', '=', $request->state);
        }

        // By user filter
        if(isset($request->user_id) && is_numeric($request->user_id)) {
            $posts = $posts->where('posts.user_id', "=", $request->user_id);
        }

        // Limit the amount
        $posts = $posts->limit(1000);

        // Execute the query
        $posts = $posts->get();

        foreach ($posts as $post) {
            // calculate the coeff
            $post->coeff = $this->calculateCoeff($post);
        }

        // Sorting
        if(isset($request->sort)) {
            switch ($request->sort) {
                case "date_desc":
                    $posts = $posts->sortByDesc('created_at');
                    break;
                case "date_asc":
                    $posts = $posts->sortBy('created_at');
                    break;
                case "coeff_asc":
                    $posts = $posts->sortBy('coeff');
                    break;
                case "coeff_desc":
                    $posts = $posts->sortByDesc('coeff');
                    break;
            }

        } else {
            // default sort
            $posts = $posts->sortByDesc('coeff');
        }


        return ['data' => [
            'posts' => $posts->values()->all(),
        ]];
    }


    /**
     * Display the specified resource.
     *
     * @param $id
     * @return array
     */
    public function show($id) {
        $post = DB::table('posts')
            ->select('posts.id', 'posts.created_at', 'posts.emergency', 'posts.state_id', DB::raw('count(answers.post_id) as answers_count'))
            ->join('circle_user', function ($join) {
                $join->on('posts.circle_id', '=', 'circle_user.circle_id')->orOn('posts.circle_id', '=', DB::raw(1));
            })
            ->leftJoin('answers', 'answers.post_id', '=', 'posts.id')
            ->orderBy('emergency')
            ->groupBy('posts.id', 'posts.created_at', 'posts.emergency', 'posts.state_id')
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
        if($post->state_id == env('SOLVED_STATE_ID', 2)) {
            return 0;
        }

        $date = new DateTime($post->created_at);
        $now = new DateTime();
        $diff = $date->diff($now);
        $hours = $diff->days * 24;
        $hours += $diff->h;
        return round(($hours + 1) * $post->emergency * (10 / ($post->answers_count + 1)));
    }
}

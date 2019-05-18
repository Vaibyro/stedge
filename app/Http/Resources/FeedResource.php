<?php

namespace App\Http\Resources;

use App\Post;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class FeedResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {

        $posts = DB::table('posts')
            ->select('posts.id', 'posts.created_at', 'posts.emergency', DB::raw('count(answers.post_id) as answers_count'))
            ->join('circle_users', function ($join) {
                $join->on('posts.circle_id', '=', 'circle_users.circle_id')->orOn('posts.circle_id', '=', DB::raw(1));
            })
            ->leftJoin('answers', 'answers.post_id', '=', 'posts.id')
            ->orderBy('emergency')
            ->groupBy('posts.id', 'posts.created_at', 'posts.emergency')
            ->get();
        foreach($posts as $post) {
            $date = new DateTime($post->created_at);
            $now = new DateTime();
            $diff = $date->diff($now);
            $hours = $diff->days * 24;
            $hours += $diff->h;

            // calculate the coeff
            // todo: ameliorer answer count coeff
            $post->coeff = round(($hours + 1) * $post->emergency * (10 / ($post->answers_count + 1)));
        }
        $posts = $posts->sortByDesc('coeff');

        return [
            'feed' => array_column($posts->toArray(), 'id'),
            'posts' => $posts->values()->all(),
        ];
    }
}

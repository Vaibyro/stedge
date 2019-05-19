<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Resources\AnswerResource;
use App\Http\Resources\TagResource;
use App\Tag;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrendsController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return array
     * @throws \Exception
     */
    public function index(Request $request) {
        $length = env('TRENDS_DEFAULT_LENGTH', 10);
        return ['data' => [
            $this->getPostCountPerTag($length)
        ]];
    }

    /**
     * @param int $length
     */
    private function getPostCountPerTag(int $length) {
        $tags = DB::table('tags')
            ->select('tags.id', 'tags.name', 'tags.is_official', DB::raw('count(post_tag.post_id) as posts_count'))
            ->leftJoin('post_tag', 'tags.id', '=', 'post_tag.tag_id')
            ->orderByDesc('posts_count')
            ->groupBy('tags.id', 'tags.name', 'tags.is_official')
            ->limit($length)
        ->get();
        return $tags;
    }
}

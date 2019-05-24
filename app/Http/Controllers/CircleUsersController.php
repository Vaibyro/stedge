<?php

namespace App\Http\Controllers;

use App\Circle;
use App\Http\Resources\CircleResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\TagsResource;
use App\Http\Resources\UsersResource;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CircleUsersController extends Controller
{



    public function index($id) {
        $circle = Circle::findOrFail($id);
        return [
            'users' => new UsersResource($circle->users),
            'metadata' => new CircleResource($circle)
        ];
    }

    /**
     * Add user to circle
     * @param $id
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function store($id, Request $request) {
        if(auth()->user()->id == env('PUBLIC_USER_ID', 2)) {
            throw new \Exception('Public user cannot add user to circle.');
        }

        $circle = Circle::findOrFail($id);
        $user = Auth::user();

        if($circle->user_id != $user->id) {
            throw new \Exception('Cannot add to others circles.');
        }

        $userToAdd = User::findOrFail($request->user_id);

        if (! $userToAdd->circles->contains($circle->id)) {
            $userToAdd->circles()->save($circle);
        } else {
            throw new \Exception('User already in circle.');
        }

        //$userToAdd->circles()->attach($circle);

        return [
            'state' => 'User added to circle'
        ];
    }

    /**
     * Delet user from circle
     * @param $id
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function destroy($id, Request $request)
    {
        if(auth()->user()->id == env('PUBLIC_USER_ID', 2)) {
            throw new \Exception('Public user cannot delet users from circles.');
        }

        $circle = Circle::findOrFail($id);
        $user = Auth::user();

        // Check access to owned circles
        if($circle->user_id != $user->id) {
            throw new \Exception('Cannot delete from others circles.');
        }

        // Check if users attempts to self remove
        if($request->user_id == $user->id) {
            throw new \Exception('Cannot self remove.');
        }

        $userToRemove = User::findOrFail($request->user_id);
        $userToRemove->circles()->detach($circle);

        return [
            'state' => 'User removed from circle'
        ];
    }
}

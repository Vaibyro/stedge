<?php

namespace App\Http\Controllers;

use App\Circle;
use App\Http\Resources\CircleResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\TagsResource;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CircleUsersController extends Controller
{



    public function index($id) {
        $circle = Circle::findOrFail($id);
        return $circle->users;
    }


}

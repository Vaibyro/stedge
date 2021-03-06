<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCirclesResource;
use App\Http\Resources\UsersResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }


    public function profile() {
        $user = Auth::user();
        return view('profile', compact('user', $user));
    }

    public function info($id) {
        $user = User::findOrFail($id);
        return view('user', compact('user', $user));
    }

    public function update_avatar(Request $request) {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        $avatarName = $user->id . '_avatar' . time() . '.' . request()->avatar->getClientOriginalExtension();

        $request->avatar->storeAs('avatars', $avatarName);

        // create a resized copy
        Image::make(storage_path('app/public/avatars/' . $avatarName))
            ->fit(600)
            ->save(storage_path('app/public/avatars/' . $avatarName));

        Image::make(storage_path('app/public/avatars/' . $avatarName))
            ->resize(100, 100)
            ->save(storage_path('app/public/avatars_small/' . $avatarName));

        $user->avatar = $avatarName;
        $user->save();

        return back()
            ->with('success', 'You have successfully upload image.');

    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return UsersResource
     */
    public function index(Request $request)
    {
        return new UsersResource(User::where('id', '!=', env('PUBLIC_USER_ID', 2))->get());
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return UserCirclesResource
     */
    public function show($id)
    {
        return new UserCirclesResource(User::find($id));
    }

}

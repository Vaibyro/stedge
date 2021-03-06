<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable {
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'name', 'email', 'password', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function circles() {
        return $this->belongsToMany('App\Circle');
    }

    public function posts() {
        return $this->hasMany('App\Post');
    }

    public function answers() {
        return $this->hasMany('App\Answer');
    }

    public function likes() {
        return $this->hasMany('App\Like');
    }

    public function avatarUrl(bool $small = false) {
        if($small) {
            return asset('storage/avatars_small/' . $this->avatar);
        }
        return asset('storage/avatars/' . $this->avatar);
    }

    public function setNewApiToken() {
        $this->api_token = Str::uuid();
        $this->save();
    }
}

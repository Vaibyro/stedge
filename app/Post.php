<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model {
    use SoftDeletes;

    public function tags() {
        return $this->belongsToMany('App\Tag');
    }

    public function answers() {
        return $this->hasMany('App\Answer');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function state() {
        return $this->belongsTo('App\State');
    }

    public function circle() {
        return $this->belongsTo('App\Circle');
    }
}

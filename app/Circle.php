<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Circle extends Model
{
    use SoftDeletes;

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function posts() {
        return $this->hasMany('App\Post');
    }
}

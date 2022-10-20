<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['post_id', 'comment'];

    public function post()
    {
        return $this->hasOne('App\Post');
    }
    public function posts()
    {
        return $this->hasMany('App\Post');
  
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    protected $fillable = ['post_id', 'name'];

    public function post()
    {
        return $this->hasOne('App\Post');
    }
    public function posts()
    {
        return $this->hasMany('App\Post');
  
    }


}

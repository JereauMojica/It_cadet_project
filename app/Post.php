<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'name', 'title' ,'detail' , 'created_at' , 'company_id' , 'department_id' , 'comment_id'
    ];

    public function company_info()
    {
        return $this->belongsTo('App\Company','company_id', 'id');
  
    } 
    
    public function department_info()
    {
        return $this->belongsTo('App\Department','department_id','id');
  
    } 

    public function comments()
    {
        return $this->hasMany('App\Comment','comment','id');
  
    } 
   
    public function attachment()
    {
        return $this->hasOne('App\Attachment');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FbLive extends Model
{
    //
    protected $fillable = ['fb_video_id', 'title', 'answers', 'created_user', 'updated_user', 'created_at', 'updated_at'];
    protected  $table="fb_lives";
    public function author()
    {
        return $this->belongsTo('App\User','created_user','id');
    }
    public function updater()
    {
        return $this->belongsTo('App\User','updated_user','id');
    }
}

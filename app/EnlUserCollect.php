<?php

namespace App;
use ff;
use Illuminate\Database\Eloquent\Model;

class EnlUserCollect extends Model
{
    protected $table = 'enl_users_collects';
    protected $connection = 'mysql';
    //
    public function article()
    {
        return $this->hasOne('App\Article','id','article_id');
    }
    public function user()
    {
        return $this->belongsTo('App\AvUser','user_id','id');
    }
    public function ez_map()
    {
        //return $this->hasMany('App\Comment', 'foreign_key', 'local_key');

        return $this->hasOne('App\ArticleMap','articles_id','article_id')->where ('site_id','=',ff\config('site_id'));
        //   return $this->belongsTo('App\ArticleMap','id','');
    }
}

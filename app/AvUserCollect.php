<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AvUserCollect extends Model
{
    protected $table = 'av_users_collects';
    protected $connection = 'mysql';
    protected $fillable = ['av_user_id','article_id'];
    //
    public function article()
    {
        return $this->belongsTo('App\Article','article_id','id');
    }
    public function user()
    {
        return $this->belongsTo('App\AvUser','user_id','id');
    }

}

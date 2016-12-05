<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCollect extends Model
{
    protected $table = 'users_collects';
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

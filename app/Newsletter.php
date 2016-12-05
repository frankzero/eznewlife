<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $fillable = [ 'title', 'content','send_at','id','created_user','updated_user'];
    public function author()
    {
        return $this->belongsTo('App\User','created_user','id');
    }
    public function updater()
    {
        return $this->belongsTo('App\User','updated_user','id');
    }
}

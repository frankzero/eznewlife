<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Animation extends Model
{


    protected $fillable = [ 'title','photo','id','created_user','updated_user','photo_size','photo_width','photo_height','feedback','org_photo'];
    protected $connection = 'mysql';
    public function author()
    {
        return $this->belongsTo('App\User','created_user','id');
    }
    public function updater()
    {
        return $this->belongsTo('App\User','updated_user','id');
    }
    //
}

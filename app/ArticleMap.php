<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleMap extends Model
{
    //
    protected $connection = 'mysql';
    protected $table = 'articles_map';
    protected $fillable = ['id', 'unique_id', 'site_id','articles_id'];
    public $timestamps = false;
}

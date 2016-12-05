<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    protected $fillable = ['name', 'data', 'description','created_user','updated_user','domain'];
    protected $connection = 'mysql';
    public function scopeEnl($query)
    {
        return $query->where('domain', 'eznewlife.com');
    }
    public function scopeGetez($query)
    {
        return $query->where('domain', 'getez.info');
    }
    public function scopeDark($query)
    {
        return $query->where('domain', 'dark.eznewlife.com');
    }
    public function scopeComic($query)
    {
        return $query->where('domain', 'avbody.info');
    }
    public function scopeAvbody($query)
    {
        return $query->where('domain', 'avbody.info');
    }
    public function scopeGod($query)
    {
        return $query->where('domain', 'godreply.tw');
    }
}

<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cache;
class Category extends Model
{
    protected $fillable = ['id','name','description'];
    protected $connection = 'mysql';
    //
    public function articles()
    {
        return $this->hasMany('App\Article');
    }
    public function articlesCount()
    {
        return $this->articles()
            ->selectRaw('created_user, count(*) as aggregate')
            ->groupBy('created_user');
    }
    public static function getList(){
	    $parameter = explode(",", Parameter::where('domain', 'eznewlife.com')->where('name', 'categories')->first()->data);
        $category = self::whereIn('id', $parameter)->lists('name', 'id');
        return $category;
    }
    public function scopeEnlDesc($query)
    {
        $parameter = explode(",", Parameter::where('domain', 'eznewlife.com')->where('name', 'categories')->first()->data);
        return $query->whereIn('id',$parameter)->lists('description', 'id');
    }
    public function scopeDarkDesc($query)
    {
        $parameter = explode(",", Parameter::where('domain', 'dark.eznewlife.com')->where('name', 'categories')->first()->data);
        return $query->whereIn('id',$parameter)->lists('description', 'id');
    }
    public function scopeGetezDesc($query)
    {
        $parameter = explode(",", Parameter::where('domain', 'avbody.info')->where('name', 'categories')->first()->data);
        return $query->whereIn('id',$parameter)->lists('description', 'id');
    }
    public function scopeAvbodyDesc($query)
    {
        $parameter = explode(",", Parameter::where('domain', 'getez.info')->where('name', 'categories')->first()->data);
        return $query->whereIn('id',$parameter)->lists('description', 'id');
    }
    public function scopeGodDesc($query)
    {
        $parameter = explode(",", Parameter::where('domain', 'godreply.tw')->where('name', 'categories')->first()->data);
        return $query->whereIn('id',$parameter)->lists('description', 'id');
    }
    /*
     *
     * dark的類別
     * */
    public function scopeDark($query)
    {
        $parameter = explode(",", Parameter::where('domain', 'dark.eznewlife.com')->where('name', 'categories')->first()->data);
        return $query->whereIn('id',$parameter)->lists('name', 'id');

    }

    public function scopeComic($query)
    {
        $parameter = explode(",", Parameter::where('domain', 'avbody.info')->where('name', 'categories')->first()->data);
        return $query->whereIn('id',$parameter)->lists('name', 'id');

    }
    public function scopeAvbody($query)
    {
        $parameter = explode(",", Parameter::where('domain', 'avbody.info')->where('name', 'categories')->first()->data);
        return $query->whereIn('id',$parameter)->lists('name', 'id');

    }
    public function scopeGod($query)
    {
        $parameter = explode(",", Parameter::where('domain', 'godreply.tw')->where('name', 'categories')->first()->data);
        return $query->whereIn('id',$parameter)->lists('name', 'id');

    }
    public function scopeGetez($query)
    {
        $parameter = explode(",", Parameter::where('domain', 'getez.info')->where('name', 'categories')->first()->data);
        return $query->whereIn('id',$parameter)->lists('name', 'id');

    }
}

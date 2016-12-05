<?php

namespace App;
use willvincent\Rateable\Rateable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Conner\Tagging\Taggable;
use ff;
use Cache;
class Article extends Model
{
    use Rateable;
    use SoftDeletes;
    use Taggable;
    protected $fillable = ['category_id', 'title', 'content','publish_at','photo','status','id','created_user','updated_user','flag','summary','instant'];
    protected $dates = ['deleted_at'];
    protected $connection = 'mysql';
    public function author()
    {
        return $this->belongsTo('App\User','created_user','id');
    }
    public function updater()
    {
        return $this->belongsTo('App\User','updated_user','id');
    }
    public function category()
    {
        return $this->belongsTo('App\Category','category_id','id');
    }
    public function collect()
    {
        return $this->belongsTo('App\UserCollect','id','article_id');
    }
    public function ez_map()
    {
        //return $this->hasMany('App\Comment', 'foreign_key', 'local_key');

        return $this->hasMany('App\ArticleMap','articles_id','id');
        //   return $this->belongsTo('App\ArticleMap','id','');
    }


    /**enl 的看版文章*/
    public function scopeExpert($query)
    {
        return $query->whereIn('category_id', [1,2])->where('status',1);
    }
    /**enl 的其它文章 濾掉expert 那5篇文章*/
    public function scopeOther($query, $filter_id=null)
    {
        // return $query->where('category_id','>', 1)->where('status',1);
        //if($filter_id===null ) return $query->where('status',1);
        // return $query->whereNotIn('id', $filter_id)->where('status',1);
        $parameter = explode(",", Parameter::where('domain', 'eznewlife.com')->where('name', 'categories')->first()->data);
        $query=$query->whereIn('category_id',$parameter)->where('status',1);
        if(!empty($filter_id)) $query=$query->whereNotIn('id', $filter_id);
        return $query;
    }
    /**only app 的文章*/
    public function scopeApp($query)
    {
        return $query->whereIn('category_id',[5])->where('status',1);

    }
    /**
     * eznewlife的類別
     */
    public function scopeEnl($query)
    {
        //$parameter = explode(",", Parameter::where('domain', 'eznewlife.com')->where('name', 'categories')->first()->data);
        $parameter=$this->get_parameter('enl_parameters');
        return $query->whereIn('category_id',$parameter)->where('status',1);

    }
    /**
     * 中肯鮮聞的類別
     */
    public function scopeGetez($query)
    {
        //$parameter = explode(",", Parameter::where('domain', 'getez.info')->where('name', 'categories')->first()->data);
        $parameter=$this->get_parameter('getez_parameters');
        return $query->whereIn('category_id',$parameter)->where('status',1);

    }

    /**
     *dark的類別
     * */
    public function scopeDark($query)
    {   //$parameter = explode(",", Parameter::where('domain', 'dark.eznewlife.com')->where('name', 'categories')->first()->data);
        $parameter=$this->get_parameter('dark_parameters');
        return $query->whereIn('category_id',$parameter)->where('status',1);

    }
    /**
     *comic的類別
     * */
    public function scopeComic($query)
    {   //$parameter = explode(",", Parameter::where('domain', 'avbody.info')->where('name', 'categories')->first()->data);
        $parameter=$this->get_parameter('avbody_parameters');
        return $query->whereIn('category_id',$parameter)->where('status',1);

    }
    /**
     *avbody的類別
     * */
    public function scopeAvbody($query)
    {   // $parameter = explode(",", Parameter::where('domain', 'avbody.info')->where('name', 'categories')->first()->data);

        $parameter=$this->get_parameter('avbody_parameters');
        return $query->whereIn('category_id',$parameter)->where('status',1);

    }
    /*god的類別
    * */
    public function scopeGod($query)
    {   // $parameter = explode(",", Parameter::where('domain', 'avbody.info')->where('name', 'categories')->first()->data);

        $parameter=$this->get_parameter('god_parameters');
        return $query->whereIn('category_id',$parameter)->where('status',1);

    }
    public function get_parameter($name){

        if (cache_has($name)){
            $parameter=cache_get($name);
            $parameter=explode(",",$parameter['categories']);
        }
        return $parameter;
    }
    public function scopeRand($query)
    {
        return $query->where('status',1);

    }
    public function scopePublish($query)
    {
        return $query->where('status',1);

    }
    public function setUpdatedAt($value)
    {
        // Do nothing.
    }
    // 達人最新文章
    public static function getexpert($n=5){
        return self::publish()->expert()->with('ez_map')->orderBy('publish_at', 'desc')->take($n)->get();
    }

    // 取得文章 n=筆數 , paged=頁數
    public static function paged($paged=1, $n=4){
        //echo 'aaaa';
        $r = self::publish()->other()->with('ez_map')->orderBy('publish_at', 'desc')->paginate($n, ['*'], 'paged', $paged);
        //echo DB::table('articles')->toSql();
        return $r;
    }


    // 隨機文章
    public static function rand($n=10){
        return self::publish()->with('author')->with('ez_map')->orderByRaw("RAND()")->take($n)->get();
    }

    // 取得分類資料
    public static function data_category($category_id, $paged,  $n=10){
        $article = self::publish()->with('ez_map')->with('tagged')->where('category_id', $category_id)->orderBy('publish_at', 'desc')->paginate($n, ['*'], 'paged', $paged );
        $article->setPath(''); //重要
        return $article;

    }


}

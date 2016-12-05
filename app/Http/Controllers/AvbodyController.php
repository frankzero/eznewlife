<?php
namespace App\Http\Controllers;

use App\Parameter;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Article, App\Category, App\ArticleMap,App\AvUserCollect,App\AvUser;
use Carbon\Carbon,App\AvUserLog;
use DB;
use ff;
use Cache;
use Auth, Input;
use Jenssegers\Agent\Agent;
use Cookie;
use Response;
//use Illuminate\Support\Facades\Event;
//use App\Events\AvUserChecked;
use Session,URL;
//use Illuminate\Session\Store;
use willvincent\Rateable\Rating;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
class AvbodyController extends Controller
{
    public function  notfound(){
        $agent = new Agent();
        $page = ['title' => '404', 'sub_title' =>'AvBody 頁面不存在',
            'url' => '', 'share_url' => url("/"), 'photo' => 'http://avbody.info/images/notfound.png'];
        return view('avbodies.404')->with('plan', 2)
            ->with('mobile', $agent->isMobile())
            ->withPage($page);//->with('categories', $categories);
    }
    public function  tool(){
        $agent = new Agent();
        $page = ['title' => 'iPhoto APP 說明', 'sub_title' =>'AVBODY',
            'url' => '', 'share_url' => url("/"), 'photo' => 'http://avbody.info/images/notfound.png'];
        return view('avbodies.tool')->with('plan', 2)
            ->with('mobile', $agent->isMobile())
            ->withPage($page);//->with('categories', $categories);
    }
    public function log( ){
        $agent = new Agent();
        if (Auth::av_user()->check()){
            AvUserLog::create(['av_user_id'=>Auth::av_user()->get()->id,
                'session'=>Session::getId(),'agent'=>$agent->device(),'ip'=>\Illuminate\Support\Facades\Request::ip(),
                'refer'=>URL::previous(),'url'=>\Illuminate\Support\Facades\Request::url(),'record_date'=>date("Y-m-d")
            ]);
            if (Auth::av_user()->get()->login_date!=date("Y-m-d")){

                $av=new AvUser();
                $av->setConnection('master');
                $av=$av->where('id', Auth::av_user()->get()->id)->first();
                $av->login_date=date("Y-m-d");
                $av->login_counts=Auth::av_user()->get()->login_counts+1;
                $av->save();
            }
        }
    }
    public function index( Request $request)
    {
        $agent = new Agent();
        //Cookie::queue(Cookie::forget('adult'));
      $this->log();
//echo "aaaaa".Auth::av_user()->getName().\Illuminate\Contracts\Auth\Authenticatable->getRememberToken();
//Illuminate\Contracts\Auth\Authenticatable
     //  echo Auth::av_user()->getSession();
        $collects=av_user_info();

      //  Session::getId();
        $categories = cache_get('avbody_categories');
      //  dd(Auth::av_user()->get());
        //Event::fire(new AvUserChecked(Auth::av_user()->check()));
       // Event::fire(new AvUserChecked(Auth::av_user()->get()->id));
        $expiresAt = Carbon::now()->addMinutes(5);
        $share_url = rawurldecode(route('avbodies.index'));

        /**取得getez ids 的文章*/

        $avbody=cache_get('avbody_ids');
        $limit=get_limit(count($avbody),Input::get('new_page',1),12);

        $avbody_ids=array_slice($avbody,'-12'*Input::get('new_page',1),$limit);
        $articles=[];
        foreach ($avbody_ids as $k=>$avbody_id){
            if (cache_has('article_' . $avbody_id)) {
                $articles[$k]= cache_get('article_' . $avbody_id);
            }
        }
        $articles=$this->paginate(array_reverse($articles),12,count($avbody),'new_page');

        $tmp_cache = 'avbody_best_articles_15' . Input::get('best_page');
        if (cache_has($tmp_cache)) {
            $best_articles = cache_get($tmp_cache);
        } else {
            $best_articles_id = Article::publish()->avbody()->orderBy('score')->lists('id')->toArray();
            //先不要 on('master')-> 非緊急性， 因為35文章資料庫也會同步43
            $best_limit=get_limit(count($avbody),Input::get('best_page',1),12);
            $avbody_ids=array_slice( $best_articles_id ,'-12'*Input::get('best_page',1),$best_limit);
            $best_articles=[];
            foreach ($avbody_ids as $k=>$avbody_id){
                if (cache_has('article_' . $avbody_id)) {
                    $best_articles[$k]= cache_get('article_' . $avbody_id);
                }
            }
            $best_articles=$this->paginate(array_reverse($best_articles), 12,count($avbody),'best_page');
            cache_put($tmp_cache, $best_articles, $expiresAt);
        }




        //1.手機專屬 2.桌機上方 3.正方形

        $page = ['title' => 'AVBODY', 'sub_title' => 'AVBODY',
            'url' => url(''), 'share_url' => url(''), 'share_url' => url(''), 'photo' => $articles[0]->photo];       // $rand_articles =
//dd($collects->toArray());
        return view('avbodies.index')->with('articles', $articles)
            //  ->with('rand_articles', $rand_articles)
            ->with('collects', $collects)
            ->with('best_articles', $best_articles)
            ->with('plan', 2)
            ->with('categories', $categories)
            ->with('mobile', $agent->isMobile())
            ->withPage($page);//->with('categories', $categories);
    }

    public function paginate($items,$perPage,$count,$pageName='page')
    {
       // $page=Paginator::resolveCurrentPage($pageName)->appends(['tab' => 'new','best_page'=>Input::get('best_page')]);
        return new LengthAwarePaginator($items, $count, $perPage,Paginator::resolveCurrentPage($pageName),
            array('path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,'best_page'=>Input::get('best_page'),'new_page'=>Input::get('new_page')));
    }
    protected function get_rand_data($count){
        /**
         * 亂數
         */
        $rand=cache_get('avbody_ids');
        $rand_article_ids = array_rand($rand,$count*2);
        $i=0;
        $rand_articles=[];
        foreach ($rand_article_ids as $k=>$rand_key){
            $rand_id=$rand[$rand_key];
            if (cache_has('article_' . $rand_id)) {
                $rand_articles[$i]= cache_get('article_' . $rand_id);
                $i++;
            }
            if ($i>=$count) break;
        }
        return $rand_articles;
    }
    protected function get_category()
    {
        /*** 取得類別資料**/
        $tmp_cache='avbody_categories';
        if (cache_has($tmp_cache)) {
            $categories  = cache_get($tmp_cache);
        } else {
            $categories= Category::getList();
            cache_forever($tmp_cache,  $categories);
        }
        return $categories;
    }
    public function miu($id, $title = "")
    {
        $collects=av_user_info();
        /** * 類別    **/
//dd($collects);
        $categories=$this->get_category();
        $parameter =cache_get('avbody_parameters');
        $data = Input::all();
        $expiresAt = Carbon::now()->addMinutes(5);
        /**讀取文章頁面 cache**/
        $tmp_cache='article_map_' . $id;
        if (cache_has($tmp_cache)) {
            $article = cache_get($tmp_cache);
        } else {
            abort(404, "文章不存在");
        }

        if ($article->type!='avbody') abort(404, "#" . $id . "文章不在這兒");
        if (!$article) abort(404, "#" . $id . "文章不存在");


        $share_url = route('avbodies.show', ['id' => $article->ez_map[0]->unique_id]);

        $page = ['title' => $article->category->name, 'sub_title' => $article->title,
            'url' => '', 'share_url' => $share_url, 'photo' => $article->photo];

        $plan = $article->plan;
        //dd($article);
        /*** 亂數 */
        $rand_articles=$this->get_rand_data(12);

        $agent = new Agent();


        if (Auth::av_user()->check()==false) {
            $article->content = article_comic_content($article->content);
        } else {
            if ($agent->isMobile()) $ad_code=15;else $ad_code=14;
            $article->content = avuser_article_comic_content($article->content,$ad_code);
        }

        //分頁處理
        $article = content_paging($article);
        //投票人數 //只有單一db 可以cache
        $tmp_var_count='article_score_count_'.$id;
        // if (cache_has($tmp_var_count)) {
        //  $rate_count = cache_get($tmp_var_count);
        //dd(["cache",$rate_count]);
        // } else {
        $rate_count=$article->ratings()->count();
        //   cache_put($tmp_var_count, $rate_count, $expiresAt);
        //  dd(["no cache",$rate_score]);
        // dd([$article,$rate_count]);
        //  }
        //  dd($rate_count);
        //投票成積 //只有單一db 可以cache
        // $tmp_var='article_score_'.$id;
        //  if (cache_has($tmp_var)) {
        //   $rate_score = cache_get($tmp_var);
        // } else {
        $rate_score=sprintf('%0.1f', round($article->averageRating(),1));
        //    cache_put($tmp_var, $rate_score, $expiresAt);
        // dd($article);
        // }

        $content= view('avbodies.show')->withArticle($article)
            ->with('rand_articles', $rand_articles)
            ->with('plan', $plan)
            ->with('rate_count',  $rate_count)
            ->with('rate_score',  $rate_score)
            ->with('parameter', $parameter)
            ->with('categories', $categories)
            ->with('collects', $collects)
            ->with('mobile', $agent->isMobile())
            ->withPage($page);

        //  \fcache::make()->save($content)->costTime();
        return $content;
        //->withPrev($prev)->withNext($next);
        // ->with('prev_link', $prev_link)
        // ->with('next_link', $next_link)
        // ->with('prev_title', $prev_title)
        // ->with('next_title', $next_title)
        //  ->with('categories', $categories);
    }
    public function show($id, $title = "")
    { $this->log();
        $collects=av_user_info();
        /** * 類別    **/
//dd($collects);
        $categories=$this->get_category();
        $parameter =cache_get('avbody_parameters');
        $data = Input::all();
        $expiresAt = Carbon::now()->addMinutes(5);
        /**讀取文章頁面 cache**/
        $tmp_cache='article_map_' . $id;
        if (cache_has($tmp_cache)) {
            $article = cache_get($tmp_cache);
        } else {
            $has_article=ArticleMap::where('unique_id',$id)->first();
            if ($has_article) {
                $article =save_article_cache($has_article->articles_id);
            }else {
                abort(404, "文章不存在");
            }
        }

        if ($article->type!='avbody') abort(404, "#" . $id . "文章不在這兒");
        if (!$article) abort(404, "#" . $id . "文章不存在");


        $share_url = route('avbodies.show', ['id' => $article->ez_map[0]->unique_id]);

        $page = ['title' => $article->category->name, 'sub_title' => $article->title,
            'url' => '', 'share_url' => $share_url, 'photo' => $article->photo];

        $plan = $article->plan;
        //dd($article);
        /*** 亂數 */
        $rand_articles=$this->get_rand_data(12);

        $agent = new Agent();


        if (Auth::av_user()->check()==false) {
            $article->content = article_comic_content($article->content,$article->title);
        } else {
            if ($agent->isMobile()) $ad_code=15;else $ad_code=14;
            $article->content = avuser_article_comic_content($article->content,$ad_code,$article->title);
        }

        //分頁處理
        $article = content_paging($article);
        //投票人數 //只有單一db 可以cache
        $tmp_var_count='article_score_count_'.$id;
       // if (cache_has($tmp_var_count)) {
          //  $rate_count = cache_get($tmp_var_count);
            //dd(["cache",$rate_count]);
       // } else {
            $rate_count=$article->ratings()->count();
         //   cache_put($tmp_var_count, $rate_count, $expiresAt);
          //  dd(["no cache",$rate_score]);
          // dd([$article,$rate_count]);
      //  }
      //  dd($rate_count);
        //投票成積 //只有單一db 可以cache
       // $tmp_var='article_score_'.$id;
        //  if (cache_has($tmp_var)) {
         //   $rate_score = cache_get($tmp_var);
       // } else {
            $rate_score=sprintf('%0.1f', round($article->averageRating(),1));
        //    cache_put($tmp_var, $rate_score, $expiresAt);
            // dd($article);
       // }
        /**iso时间**/
        $dt = Carbon::parse($article->publish_at);
        $article->publish_at_iso=$dt->toIso8601String();
        $dt = Carbon::parse($article->updated_at);
        $article->updated_at_iso=$dt->toIso8601String();
        $dt = Carbon::parse($article->created_at);
        $article->created_at_iso=$dt->toIso8601String();
        $content= view('avbodies.show')->withArticle($article)
            ->with('rand_articles', $rand_articles)
            ->with('plan', $plan)
            ->with('rate_count',  $rate_count)
            ->with('rate_score',  $rate_score)
            ->with('parameter', $parameter)
            ->with('categories', $categories)
            ->with('collects', $collects)
            ->with('mobile', $agent->isMobile())
            ->withPage($page);

        //  \fcache::make()->save($content)->costTime();
        return $content;
        //->withPrev($prev)->withNext($next);
        // ->with('prev_link', $prev_link)
        // ->with('next_link', $next_link)
        // ->with('prev_title', $prev_title)
        // ->with('next_title', $next_title)
        //  ->with('categories', $categories);
    }
    public function collect($id)
    {
      //  dd(Auth::av_user()->get()->id);
        if (Auth::av_user()->check()==false) return [];
        $data = Input::all();

        $collect=new AvUserCollect();
        $collect->setConnection('master');
        //dd($a);
        $tmp_cache='user_collect_' . Auth::av_user()->get()->id;
        cache_forget($tmp_cache);
        $av_user_id=Auth::av_user()->get()->id;
        $collect=$collect->where('article_id',$id)->where('av_user_id',$av_user_id);
        //dd($collect->writConnection());
//	//$collect->connection('write');
        // dd($collect);

        if ($collect->count()==0){

            $data=[];
            $data['av_user_id'] = $av_user_id;
            $data['article_id'] =$id;


            $collect=AvUserCollect::on('master')->updateOrCreate($data);
           // dd($data);
            $message = " 已收藏";
            $status=1;
        } else {
            //$collect->connection('write');
            $collect->delete();
            $message = " 已取消收藏";
            $status=0;
        }
        $response=['message'=>$id.$message,'status'=>$status];
        return Response::json($response);


    }
    public function vote($id){

        $expiresAt = Carbon::now()->addMinutes(5);

        $data = Input::all();
        $article = Article::on('master')->find($id);

        //dd($article);
        if (Auth::av_user()->check()==false) {
            $response=['message'=>"您必需成為會員，才能投票",'status'=>-1,'score'=>$score = round($article->ratingPercent(5) / 20, 1),'rating'=>$data['rating']];
            return Response::json($response);
        } else {
            $av_user_id=Auth::av_user()->get()->id;//=rand ( 8 , 28);
        }

        $vote=Rating::on('master')->where('rateable_id',$id)->where('av_user_id',$av_user_id)->where('rateable_type','App\Article');

        if ($vote->count()>0){
            $message = "更新投票結果,";
            $status = 1;
            $update_vote=$vote->first();
            $update_vote->rating = $data["rating"];
            $update_vote->save();
        } else {
            $rating = new Rating; 
			$rating->setConnection('master');
            $rating->rating = $data["rating"];   
            $rating->av_user_id = $av_user_id;//Auth::user()->get()->id;
            $message = "謝謝你的投票,";
            $status = 0;
            $article->ratings()->save($rating);

        }

        $rating_score=$article->averageRating()*20;
        $rate_count=$article->ratings()->count();
        $score =sprintf('%0.1f', round($rating_score / 20, 1));
        $article->score=$rating_score;
        $article->save();

        $tmp_cache='article_' . $id;
        $cache=cache_get($tmp_cache);
        $cache->score=$rating_score;
        cache_forever($tmp_cache,  $cache);
        $message.="總評分為 ".$score;
      //  dd(  $score);
        $tmp_var='article_score_'.$id;
        $tmp_var_count='article_score_count_'.$id;
        //cache_forget($tmp_var);
       // cache_forget($tmp_var_count);
        //cache_put($tmp_var,  $rating_score, $expiresAt);
        //cache_put($tmp_var_count, $rate_count, $expiresAt);
        $response=['message'=>$message,'status'=>$status,'score'=>$score,'rate_count'=>$rate_count,'rating'=>$data['rating']];
        return Response::json($response);
       // dd(Article::find($id)->ratings);
    }

    public function rand_app()
    {
        /*
        $expiresAt = Carbon::now()->addSeconds(20);
        $tmp_var = 'rand_avbody_articles';
        if (cache_has($tmp_var)) {
            $rand_articles = cache_get($tmp_var);
        } else {
            $rand_articles = Article::publish()->avbody()->with('ez_map')->orderByRaw("RAND()")->take(3)->get();
            cache_put($tmp_var, $rand_articles, $expiresAt);
        }*/
        $rand_articles=$this->get_rand_data(3);
        $content= view('avbodies.rand_app')->withArticles($rand_articles);
       // \fcache::make()->save($content)->costTime();
        return $content;
    }


    public function login()
    {
        $agent = new Agent();
        $page = ['title' => 'ENL暗黑網', 'sub_title' => 'AVBody',
            'url' => url(''), 'share_url' => url(''), 'share_url' => url(''), 'photo' => asset('image/avbody_logo.png')
        ];// $rand_articles =

        return view('avbodies.login')
            ->with('plan', 1)
            ->with('mobile', $agent->isMobile())
            ->withPage($page);//->with('categories', $categories);
    }
    public function tag($name)
    {
        //avbody 沒有tag 不處理
        $expiresAt = Carbon::now()->addMinutes(5);
        $categories = Category::avbody();
        //記憶all選單
        $my_tags = Article::existingTags()->pluck('name')->all();
        //dd($my_tags);
        if (!in_array($name, $my_tags)) abort(404, "文章tag不存在");

        //if ($categories[$id] != $name) return redirect(route('articles.category', ['id' => $id, 'name' => $categories[$id]]));
        $tmp_cache = 'tag_avbodies_' . $name . "_" . Input::get('paged');
        if (cache_has($tmp_cache)) {
            $tag_articles = cache_get($tmp_cache);
        } else {
            $tag_articles = Article::publish()->avbody()->with('ez_map')->with('tagged')->withAnyTag([$name])->orderBy('publish_at', 'desc')->paginate(10, ['*'], 'paged');
            cache_put($tmp_cache, $tag_articles, $expiresAt);
        }

        $tag_articles->setPath(''); //重要
        if ($tag_articles->count() == 0) abort(404, "文章tag不存在");

        $share_url = rawurldecode(route('avbodies.tag', ['name' => $name]));

        $page = ['title' => 'AVBody', 'sub_title' => $name,
            'url' => '', 'share_url' => $share_url, 'photo' => $tag_articles[0]->photo];
        $tmp_cache='avbody_rand_articles_' . $name;
        if (cache_has($tmp_cache)) {
            $rand_articles = cache_get($tmp_cache);
        } else {
            $rand_articles = Article::publish()->avbody()->with('ez_map')->orderByRaw("RAND()")->take(10)->get();
            cache_put($tmp_cache, $rand_articles, $expiresAt);
        }


        //   dd( $tag_articles);
        return view('avbodies.tag')
            ->with('rand_articles', $rand_articles)
            ->with('tag_articles', $tag_articles)
            ->with('plan', 2)
            ->withPage($page)->with('categories', $categories);
    }

    /**
     * 轉址處理 成人網頁
     */

    public function adult()
    {
        $data = Input::all();

        Cookie::queue('adult', 1, 240);
        if (strpos($data['url'], "avbody.eznewlife.com")) {
            $refer = $data['url'];
        } else {
            $refer = route("avbodies.index");
        }
        // echo $refer;
        return redirect($refer);
    }

    public function _index()
    {
        //Cookie::queue(Cookie::forget('adult'));

        $collects=av_user_info();
        $categories = Category::avbody();
        //   dd($categories);

        $expiresAt = Carbon::now()->addMinutes(5);
        $share_url = rawurldecode(route('avbodies.index'));
        //  $categories = Category::lists('name', 'id');
        //  Cookie::get('name');
        if (Cookie::get('adult') == 1) {
            //  dd("adult");
        } else {
            //  dd("child");
        }
        $tmp_cache = 'avbody_index_articles_15_' . Input::get('new_page');
        if (cache_has($tmp_cache)) {
            $articles = cache_get($tmp_cache);
        } else {
            $articles = Article::publish()->avbody()->with('author', 'ez_map')->orderBy('publish_at', 'desc')->paginate(15,['*'],'new_page');
            cache_put($tmp_cache, $articles, $expiresAt);
        }
        //  dd($articles->currentPage());


        $tmp_cache = 'avbody_best_articles_15' . Input::get('best_page');
        if (cache_has($tmp_cache)) {
            $best_articles = cache_get($tmp_cache);
        } else {
            $best_articles =  Article::publish()->avbody()->with('author', 'ez_map')->orderBy('score', 'desc')->paginate(15,['*'],'best_page');
            cache_put($tmp_cache, $best_articles, $expiresAt);
        }
        $agent = new Agent();
        //1.手機專屬 2.桌機上方 3.正方形

        $page = ['title' => 'H-Avbody', 'sub_title' => '',
            'url' => url(''), 'share_url' => url(''), 'share_url' => url(''), 'photo' => $articles[0]->photo];       // $rand_articles =

        return view('avbodies.index')->with('articles', $articles)
            //  ->with('rand_articles', $rand_articles)
            ->with('collects', $collects)
            ->with('best_articles', $best_articles)
            ->with('plan', 2)
            ->with('categories', $categories)
            ->with('mobile', $agent->isMobile())
            ->withPage($page);//->with('categories', $categories);
    }


    public function id_to_article_id($id)
    {
        $article_id = DB::table('articles_map')
            ->where('site_id', ff\config('site_id'))
            ->where('unique_id', $id)
            ->select(['articles_id'])
            ->first();

        if (isset($article_id->articles_id)) {
            $article_id = $article_id->articles_id;
        } else {
            $article_id = 0;
        }

        return $article_id;
    }

    public function search()
    { $this->log();
        $collects=av_user_info();
        $categories = $this->get_category();

        $data = Input::all();
        // $article_id = $this->id_to_article_id($id);
        $expiresAt = Carbon::now()->addMinutes(5);
        $parameter =cache_get('avbody_parameters');
        if (Input::has('q') and !empty(Input::get('q'))) {
            $q = Input::get('q');
        } else {
            return redirect(url("/"));
        }
        $share_url = url("/");
        $page = ['title' => 'AVBody', 'sub_title' => '搜尋「'.$q."」- AVBody",
            'url' => '', 'share_url' => $share_url, 'photo' => ''];
        $search_ids = Article::publish()->avbody()
            ->where(function ($query) {
                $query->where('title', 'like', '%' . Input::get('q') . '%')
                    ->orwhere('content','like', '%' . Input::get('q') . '%');
            })
            ->orderBy('publish_at', 'desc')->lists('id')
            ->toArray();
        $search_ids_data=array_slice($search_ids ,15*(Input::get('page',1)-1),15);
        $articles=[];
        foreach ($search_ids_data as $k=>$search_id){
            if (cache_has('article_' . $search_id)) {
                $articles[$k]= cache_get('article_' . $search_id);
            }
        }
        $articles=$this->paginate(($articles), 15,count($search_ids));
        $articles->setPath(''); //重要
        // $articles =$articles->toArray();


        $agent = new Agent();
        return view('avbodies.search')->with('articles', $articles)
            ->with('plan', 2)
            ->with('q', $q)
			 ->with('collects', $collects)
            ->with('mobile', $agent->isMobile())
            ->with('categories', $categories)
            ->withPage($page);//->with('categories', $categories);

    }

    public function _show($id, $title = "")
    {
        $collects=av_user_info();
//dd($collects);
        $categories = Category::avbody();

//dd(Auth::av_user()->get()->collects()->get());

        $parameter = Parameter::avbody()->lists('data', 'name');
        $data = Input::all();
        $expiresAt = Carbon::now()->addMinutes(5);
        /**讀取文章頁面 cache
         * 與enl共用cache 所以也讀了 category tagged
         **/

        if (cache_has('article_' . $id)) {
            $article = cache_get('article_' . $id);
            $article_id = $article->id;
        } else {
            $article_id = $this->id_to_article_id($id);
            $article = Article::with('author', 'ez_map', 'category', 'tagged')->find($article_id);
            cache_put('article_' . $id, $article, $expiresAt);
            // dd($article);
        }

        if (!array_key_exists($article->category_id,  $categories->toArray())) abort(404, "#" . $id . "文章不在這兒");
        if (!$article) abort(404, "#" . $id . "文章不存在");
        $data = Input::all();


        $share_url = route('avbodies.show', ['id' => $article->ez_map[0]->unique_id]);

        $page = ['title' => $article->category->name, 'sub_title' => $article->title,
            'url' => '', 'share_url' => $share_url, 'photo' => $article->photo];

        $plan = 1;
        if ($article->flag === 'P') $plan = 2;
        $tmp_cache = 'avbody_rand_articles_6_' . $id;
        if (cache_has($tmp_cache)) {
            $rand_articles = cache_get($tmp_cache);
        } else {
            $rand_articles = Article::publish()->avbody()->with('author', 'ez_map')->orderByRaw("RAND()")->take(6)->get();
            cache_put($tmp_cache, $rand_articles, $expiresAt);
        }

        $agent = new Agent();
        if (Auth::av_user()->check()==false) {
            $article->content = article_comic_content($article->content);
        } else {
            if ($agent->isMobile()) $ad_code=15;else $ad_code=14;
            $article->content = avuser_article_comic_content($article->content,$ad_code);
        }
        //分頁處理
        $article = content_paging($article);



        $content= view('avbodies.show')->withArticle($article)
            ->with('rand_articles', $rand_articles)
            ->with('plan', $plan)
            ->with('parameter', $parameter)
            ->with('categories', $categories)
            ->with('collects', $collects)
            ->with('mobile', $agent->isMobile())
            ->withPage($page);

      //  \fcache::make()->save($content)->costTime();
        return $content;
        //->withPrev($prev)->withNext($next);
        // ->with('prev_link', $prev_link)
        // ->with('next_link', $next_link)
        // ->with('prev_title', $prev_title)
        // ->with('next_title', $next_title)
        //  ->with('categories', $categories);
    }


}

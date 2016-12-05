<?php
namespace App\Http\Controllers;

use App\Parameter;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Article, App\Category,App\ArticleMap;
use Carbon\Carbon;
use DB;
use ff,Cache;
use Auth,Input;
use Jenssegers\Agent\Agent;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
class GetezController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function  notfound(){
        $agent = new Agent();
        $page = ['title' => '404', 'sub_title' =>'GetEz-中肯新聞 頁面不存在',
            'url' => '', 'share_url' => url("/"), 'photo' => 'http://demo.eznewlife.com/images/news_logo.png'];
        return view('getezs.404')->with('plan', 1)
            ->with('mobile', $agent->isMobile())
            ->withPage($page);//->with('categories', $categories);
    }

    public function paginate($items,$perPage,$count)
    {

        return new LengthAwarePaginator($items, $count, $perPage,Paginator::resolveCurrentPage('page'),
            array('path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page'));
    }
    protected function get_rand_data($count){
        /**
         * 亂數
         */
        $rand=cache_get('getez_ids');
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
    public function get_category()
    {
        /*** 取得類別資料**/
        $tmp_cache='getez_categories';
        if (cache_has($tmp_cache)) {
            $categories  = cache_get($tmp_cache);
        } else {
            $categories= Category::getList();
            cache_forever($tmp_cache,  $categories);
        }
        return $categories;
    }
    protected function get_rand_tag_data($count,$article){
        /**
         * 亂數
         */
        $rand_articles=$tmp_rand=array();
        $tags=$article->tags->pluck('name')->all();
        $category_ids=array_keys($this->get_category()->toArray());
        $tmp_array=[0];
        $j=0;
        foreach ($tags as $k=>$name){
            /**取得tag文章*/
            $tmp_cache = 'enl_tag_ids_' . $name;
            if (cache_has($tmp_cache)) {

                $tag_articles = array_reverse(cache_get($tmp_cache));
                //日本、女夏
                foreach ($tag_articles as $k => $tag_id) {

                    if (cache_has('article_' . $tag_id) and $tag_id != $article->id and !in_array($tag_id,$tmp_array)) {


                        $tmp_article = cache_get('article_' . $tag_id);
                        if (in_array($tmp_article->category_id, $category_ids)) {
                            $j++;
                            $rand_articles[$j] = $tmp_article;
                            $tmp_array[]=$rand_articles[$j]->id;
                        }

                    }
                    if ($j >= $count) break;
                }
            }
        }

        $insufficient=$count-$j;
      // dd($insufficient,$rand_articles);
        if ($insufficient>0){
            $tmp_rand=$this->get_rand_data($insufficient);
        }
        // dd([$tmp_rand,$insufficient,$rand_articles]);
        //if (count($rand_articles)>0) {
        $rand_articles = array_merge($rand_articles, $tmp_rand);


        return $rand_articles;
    }
    public function show($id, $title = "")
    {


         $data=Input::all();

        $expiresAt = Carbon::now()->addMinutes(5);
        /**讀取文章頁面 cache
        與enl共用cache 所以也讀了 category tagged
         **/

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
        if ($article->type!='enl') {
            abort(404, "文章不存在");
        }
        if (Input::get('page')>1){
            $article->content=implode("@enl@",$article->content_pages);
            $article=content_paging($article);
            $article->page=Input::get('page');
            //$article->content_current=$article->content=$article->content_pages[Input::get('page')-1];
            //  $article->page=Input::get('page');
        }
        $article->content=replace_alt($article->content,$article->title);
        $article->content=str_ireplace('<h1','<h2',  $article->content);
        $article->content=str_ireplace('</h1>','</h2>',  $article->content);

        $parameter =cache_get('getez_parameters');
        $share_url = route('getezs.show', ['id' => $article->ez_map[0]->unique_id]);

        $page = ['title' => $article->category->name, 'sub_title' => $article->title,
            'url' => '', 'share_url' => $share_url,'photo'=>$article->photo];

        $plan=$article->plan;
        /** * 亂數 */
        $rand_articles=$this->get_rand_tag_data(9,$article);

        $agent = new Agent();
        /**iso时间**/
        $dt = Carbon::parse($article->publish_at);
        $article->publish_at_iso=$dt->toIso8601String();
        $dt = Carbon::parse($article->updated_at);
        $article->updated_at_iso=$dt->toIso8601String();
        $dt = Carbon::parse($article->created_at);
        $article->created_at_iso=$dt->toIso8601String();
        return view('getezs.show')->withArticle($article)
            ->with('rand_articles', $rand_articles)
            ->with('plan', $plan)
            ->with('parameter', $parameter)
            ->with('mobile', $agent->isMobile() )
            ->withPage($page);

    }

    public function index()
    {
        $expiresAt = Carbon::now()->addMinutes(5);
        //$share_url = rawurldecode(route('articles.index'));
      //  $categories = Category::lists('name', 'id');

        $parameter =cache_get('getez_parameters');

        /**取得getez ids 的文章*/

        $getez=cache_get('getez_ids');
        $limit=get_limit(count($getez),Input::get('page',1),5);

        $getez_ids=array_slice($getez,'-5'*Input::get('page',1),$limit);
        $articles=[];
        foreach ($getez_ids as $k=>$getez_id){
            if (cache_has('article_' . $getez_id)) {
                $articles[$k]= cache_get('article_' . $getez_id);
            }
        }
        $articles=$this->paginate(array_reverse($articles), 5,count($getez));


        /** * 亂數 */
        $rand_articles=$this->get_rand_data(30);

        $agent = new Agent();
        //1.手機專屬 2.桌機上方 3.正方形

        $page = ['title' => '中肯鮮聞', 'sub_title' => '中肯鮮聞',
            'url' => url(''), 'share_url' => url('') ,'share_url' => url(''),'photo'=>$articles[0]->photo];       // $rand_articles = 


        return view('getezs.index')->with('articles', $articles)
                  ->with('rand_articles', $rand_articles)
            ->with('parameter', $parameter)
            ->with('plan', 1)
            ->with('mobile', $agent->isMobile() )
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
    {
        $data=Input::all();
        // $article_id = $this->id_to_article_id($id);
        $expiresAt = Carbon::now()->addMinutes(5);

        $parameter =cache_get('getez_parameters');


        if (Input::has('q') and !empty(Input::get('q'))){
            $q=Input::get('q');
        } else {
            return redirect(url("/"));
        }
        $share_url=url("/");

        $page = ['title' => '中肯鮮聞', 'sub_title' => '搜尋「'.$q."」- 中肯鮮聞",
            'url' => '', 'share_url' => $share_url, 'photo' => ''];

        $search_ids = Article::publish()->getez()->whereRaw('(title LIKE "%' . $q . '%" or content LIKE "%' . $q . '%")')->orderBy('publish_at', 'desc')->lists('id')
            ->toArray();
        $search_ids_data=array_slice($search_ids ,5*(Input::get('page',1)-1),5);
        $articles=[];
        foreach ($search_ids_data as $k=>$search_id){
            if (cache_has('article_' . $search_id)) {
                $articles[$k]= cache_get('article_' . $search_id);
            }
        }
        $articles=$this->paginate(array_reverse($articles), 5,count($search_ids));

     //  $articles->setPath(''); //重要
        // $articles =$articles->toArray();
        /** * 亂數 */
        $rand_articles=$this->get_rand_data(3);


        $agent = new Agent();
        return view('getezs.search')->with('articles', $articles)
            ->with('rand_articles', $rand_articles)
            ->with('plan', 1)
            ->with('parameter', $parameter)
            ->with('q', $q)
            ->with('mobile', $agent->isMobile() )
            ->withPage($page);//->with('categories', $categories);

    }
    public function _show($id, $title = "")
    {
        /*
        $tmp_cache='getez_parameter';
        if (cache_has($tmp_cache)) {
            $parameter = cache_get($tmp_cache);
        }else {
            $parameter = Parameter::getez()->lists('data', 'name');
            cache_forever($tmp_cache,  $parameter);
            // cache_put($tmp_cache, $article, $expiresAt);
        }*/
        $parameter = Parameter::getez()->lists('data', 'name');
       // dd($parameter);
        $data=Input::all();
       // $article_id = $this->id_to_article_id($id);
        $expiresAt = Carbon::now()->addMinutes(5);
        /**讀取文章頁面 cache
         與enl共用cache 所以也讀了 category tagged
         **/

        if (cache_has('article_'.$id)) {
            $article = cache_get('article_'.$id);
            $article_id=$article->id;
        }else {
            $article_id = $this->id_to_article_id($id);
            $article = Article::with('author', 'ez_map', 'category', 'tagged')->find($article_id);
            cache_put('article_'.$id, $article, $expiresAt);
            // dd($article);
        }
        if (!$article) abort(404, "#".$id."文章不存在");
        //情色文章類別強制導到 …dark.eznewlife.com
        $dark_url=check_dark_show($article);
        if (!empty($dark_url)) return redirect($dark_url);
        $data=Input::all();




       // $categories = Category::lists('name', 'id');
       // return Helper::prettyJson(['asdf' => 'qwerty'], 200);
        $share_url = route('getezs.show', ['id' => $article->ez_map[0]->unique_id]);

        $page = ['title' => $article->category->name, 'sub_title' => $article->title,
            'url' => '', 'share_url' => $share_url,'photo'=>$article->photo];
       // $prev = Article::publish()->getez()->where('publish_at', '>', $article->publish_at)->orderBy('publish_at', 'desc')->first();
       // $next =Article::publish()->getez()->where('publish_at', '<', $article->publish_at)->orderBy('publish_at', 'desc')->first();

     //   $prev_link = $this->previous_link($article->id);
       // $next_link = $this->next_link($article->id);

       // $prev_title = $this->previous_title($article->id);
       // $next_title = $this->next_title($article->id);
        $plan=1;
        if($article->flag==='P') $plan=2;
        $tmp_cache='getez_rand_articles_15_'.$id;
        if (cache_has( $tmp_cache)) {
            $rand_articles = cache_get($tmp_cache);
        } else {
            $rand_articles =Article::publish()->getez()->with('author','ez_map')->orderByRaw("RAND()")->take(15)->get();
            cache_put($tmp_cache, $rand_articles, $expiresAt);
        }
           // dd($rand_articles);
        //echo 'aaa';print_r($prev);
        $agent = new Agent();
        //1.手機專屬 2.桌機上方
        //dd($parameter);

        $article->content = article_handle_content($article->content);
      //  $article->content= preg_replace("#(<img[^>]+)src=([^>]+>)#", '$1 data-original= $2', $article->content);
        //分頁處理
        $article=content_paging($article);
       //$ad=get_ad(12,1,'getez.info');

        //dd($ad->code);
       // dd($article);

        // dd($adv);
        return view('getezs.show')->withArticle($article)
            ->with('rand_articles', $rand_articles)
            ->with('plan', $plan)
            ->with('parameter', $parameter)
            ->with('mobile', $agent->isMobile() )
            ->withPage($page);
           //->withPrev($prev)->withNext($next);
           // ->with('prev_link', $prev_link)
           // ->with('next_link', $next_link)
           // ->with('prev_title', $prev_title)
           // ->with('next_title', $next_title)
          //  ->with('categories', $categories);
    }


}

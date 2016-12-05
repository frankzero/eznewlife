<?php
namespace App\Http\Controllers;

use App\Parameter;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Article, App\Category, App\ArticleMap;
use Carbon\Carbon;
use DB;
use ff, Cache;
use Auth, Input;
use Jenssegers\Agent\Agent;
use Cookie;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
class BlackController extends Controller
{
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
        $rand=cache_get('dark_ids');
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
        $tmp_cache='dark_categories';
        if (cache_has($tmp_cache)) {
            $categories  = cache_get($tmp_cache);
        } else {
            $categories= Category::getList();
            cache_forever($tmp_cache,  $categories);
        }
        return $categories;
    }
    public function show($id, $title = "")
    {
	
        $categories=$this->get_category();

        $agent = new Agent();
        $data = Input::all();
        $expiresAt = Carbon::now()->addMinutes(5);
        /**讀取文章頁面 cache**/
        $tmp_cache='article_map_' . $id;
        if (cache_has($tmp_cache)) {
            $article = cache_get($tmp_cache);
        } else {
            abort(404, "文章不存在");
        }
        if ($article->type!='dark') {
            return redirect($article->rediret_url);
        }
        if (Input::get('page')>1){
            $article->content=implode("@enl@",$article->content_pages);
            $article=content_paging($article);
            $article->page=Input::get('page');
            //$article->content_current=$article->content=$article->content_pages[Input::get('page')-1];
            //  $article->page=Input::get('page');
        }
        $share_url = route('darks.show', ['id' => $article->ez_map[0]->unique_id]);

        $page = ['title' => $article->category->name, 'sub_title' => $article->title,
            'url' => '', 'share_url' => $share_url, 'photo' => $article->photo];

        $plan = $article->plan;

        /** * 亂數  */
        $rand_articles=$this->get_rand_data(15);
        $parameter =cache_get('dark_parameters');



        // dd($adv);
        $content = view('darks.show')->withArticle($article)
            ->with('rand_articles', $rand_articles)
            ->with('plan', $plan)
            ->with('parameter', $parameter)
            ->with('categories', $categories)
            ->with('mobile', $agent->isMobile())
            ->withPage($page);

       // \fcache::make()->save($content)->costTime();

        return $content;

    }

    public function rand_app()
    {

        $rand_articles=$this->get_rand_data(3);


        $content = view('darks.rand_app')->withArticles($rand_articles);
        //\fcache::make()->save($content)->costTime();

        return $content;
    }
    public function category($id, $name = "")
    {
        $expiresAt = Carbon::now()->addMinutes(5);
        $categories =$this->get_category();
        if (!array_key_exists($id, $categories->toArray())) abort(404, "文章類別不存在");
        $agent = new Agent();
        //1.手機專屬 2.桌機上方 3.正方形


        /**取得類別文章*/
        $ids=cache_get('ids');
        $limit=get_limit(count($ids[$id]),Input::get('page',1),10);
        $cate_ids=array_slice($ids[$id],-10*Input::get('page',1),$limit);
        //dd([$cate_ids,$ids[$id]],-10*Input::get('paged',1));
        $cate_articles=[];
        foreach ($cate_ids as $k=>$cate_id){
            if (cache_has('article_' . $cate_id)) {
                $cate_articles[$k]= cache_get('article_' . $cate_id);
            }
        }
        $cate_articles=$this->paginate(array_reverse($cate_articles), 10,count($ids[$id]));

        $share_url = rawurldecode(route('darks.category', ['id' => $id, 'name' => $name]));

        $page = ['title' => 'EzNewLife', 'sub_title' => $name,
            'url' => '', 'share_url' => $share_url, 'photo' => $cate_articles[0]->photo];

        $rand_articles=$this->get_rand_data(10);

        return view('darks.category')
            ->with('categories', $categories)
            ->with('mobile', $agent->isMobile())
            ->with('rand_articles', $rand_articles)
            ->with('articles', $cate_articles)
            ->with('plan', 1)
            ->withPage($page)->with('categories', $categories);
    }

    public function login()
    {
        $agent = new Agent();
        $page = ['title' => 'ENL暗黑網', 'sub_title' => 'EzNewLife',
            'url' => url(''), 'share_url' => url(''), 'share_url' => url(''), 'photo' => asset('image/dark_logo.png')
        ];// $rand_articles =

        return view('darks.login')
            ->with('plan', 1)
            ->with('mobile', $agent->isMobile())
            ->withPage($page);//->with('categories', $categories);
    }
    public function tag($name)
    {

        $expiresAt = Carbon::now()->addMinutes(5);
        $agent = new Agent();
        /** * 取得類別資料**/
        $categories=$this->get_category();
        /**取得tag文章*/
        $tmp_cache = 'dark_tag_ids_' . $name;
        $tag_ids = cache_get($tmp_cache);

        if (count($tag_ids) == 0) abort(404, "文章tag不存在");

        $limit=get_limit(count($tag_ids),Input::get('page',1),10);
        $cate_ids_data=array_slice($tag_ids,-10*Input::get('page',1),$limit);
        $tag_articles=[];
        foreach ($cate_ids_data as $k=>$tag_id){
            if (cache_has('article_' . $tag_id)) {
                $tag_articles[$k]= cache_get('article_' . $tag_id);
            }
        }
        $tag_articles=$this->paginate(array_reverse($tag_articles), 10,count($tag_ids));


        $share_url = rawurldecode(route('darks.tag', ['name' => $name]));

        $page = ['title' => 'EzNewLife', 'sub_title' => $name,
            'url' => '', 'share_url' => $share_url, 'photo' => $tag_articles[0]->photo];
        $rand_articles=$this->get_rand_data(10);


        //   dd( $tag_articles);
        return view('darks.tag')
            ->with('mobile', $agent->isMobile())

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
        if (strpos($data['url'], "dark.eznewlife.com")) {
            $refer = $data['url'];
        } else {
            $refer = route("darks.index");
        }
       // echo $refer;
       return redirect($refer);
    }

    public function index()
    {

		//Cookie::queue(Cookie::forget('adult'));

        $categories =$this->get_category();
     //   dd($categories);

        $expiresAt = Carbon::now()->addMinutes(5);
        $share_url = rawurldecode(route('darks.index'));
        //  $categories = Category::lists('name', 'id');
        //  Cookie::get('name');
        if (Cookie::get('adult') == 1) {
          //  dd("adult");
        } else {
          //  dd("child");
        }

        $parameter =cache_get('dark_parameters');
        /**取得dark ids 的文章*/
        $dark=cache_get('dark_ids');
        $limit=get_limit(count($dark),Input::get('page',1),5);
        $dark_ids=array_slice($dark,'-5'*Input::get('page',1),$limit);
        $articles=[];
        foreach ($dark_ids as $k=>$dark_id){
            if (cache_has('article_' . $dark_id)) {
                $articles[$k]= cache_get('article_' . $dark_id);
            }
        }
        $articles=$this->paginate(array_reverse($articles), 5,count($dark));
        $rand_articles=$this->get_rand_data(3);
        /*
        $tmp_cache = 'dark_index_articles_5_' . Input::get('page');
        if (cache_has($tmp_cache)) {
            $articles = cache_get($tmp_cache);
        } else {
            $articles = Article::publish()->dark()->with('author', 'ez_map')->orderBy('publish_at', 'desc')->paginate(5);
            cache_put($tmp_cache, $articles, $expiresAt);
        }
        $tmp_cache = 'dark_rand_articles_3';
        if (cache_has($tmp_cache)) {
            $rand_articles = cache_get($tmp_cache);
        } else {
            $rand_articles = Article::publish()->dark()->with('author', 'ez_map')->orderByRaw("RAND()")->take(3)->get();
            cache_put($tmp_cache, $rand_articles, $expiresAt);
        }*/
        $agent = new Agent();
        //1.手機專屬 2.桌機上方 3.正方形

        $page = ['title' => 'ENL暗黑網', 'sub_title' => 'EzNewLife',
            'url' => url(''), 'share_url' => url(''), 'share_url' => url(''), 'photo' => $articles[0]->photo];       // $rand_articles =

        $content = view('darks.index')->with('articles', $articles)
            ->with('rand_articles', $rand_articles)
            ->with('plan', 2)
            ->with('categories', $categories)
            ->with('mobile', $agent->isMobile())
            ->with('parameter',$parameter)
            ->withPage($page);//->with('categories', $categories);

        //\fcache::make()->save($content)->costTime();

        
        return $content;
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
        $categories =$this->get_category();

        $data = Input::all();
        // $article_id = $this->id_to_article_id($id);
        $expiresAt = Carbon::now()->addMinutes(5);
        //$parameter = Parameter::dark()->lists('data', 'name');

        $parameter =cache_get('dark_parameters');
        if (Input::has('q') and !empty(Input::get('q'))) {
            $q = Input::get('q');
        } else {
            return redirect(url("/"));
        }
        $share_url = url("/");
        $page = ['title' => 'EzNewLife', 'sub_title' => $q,
            'url' => '', 'share_url' => $share_url, 'photo' => ''];
        $search_ids = Article::publish()->dark()->whereRaw('(title LIKE "%' . $q . '%" or content LIKE "%' . $q . '%")')->orderBy('publish_at', 'desc')->lists('id')
            ->toArray();
        $articles=[];
        $search_ids_data=array_slice($search_ids ,5*(Input::get('page',1)-1),5);
        foreach ($search_ids_data as $k=>$search_id){
            if (cache_has('article_' . $search_id)) {
                $articles[$k]= cache_get('article_' . $search_id);
            }
        }
        $articles=$this->paginate(($articles), 5,count($search_ids));

        $articles->setPath(''); //重要
        // $articles =$articles->toArray();


        $rand_articles=$this->get_rand_data(3);
        $agent = new Agent();
        return view('darks.search')->with('articles', $articles)
            ->with('rand_articles', $rand_articles)
            ->with('plan', 2)
            ->with('parameter', $parameter)
            ->with('q', $q)
            ->with('mobile', $agent->isMobile())
            ->with('categories', $categories)
            ->withPage($page);//->with('categories', $categories);

    }

 


}

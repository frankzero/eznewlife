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

class ComicController extends Controller
{


    public function rand_app()
    {

        $expiresAt = Carbon::now()->addSeconds(20);
        $tmp_var = 'rand_comic_articles';
        if (cache_has($tmp_var)) {
            $rand_articles = cache_get($tmp_var);
        } else {
            $rand_articles = Article::publish()->comic()->with('ez_map')->orderByRaw("RAND()")->take(3)->get();
            cache_put($tmp_var, $rand_articles, $expiresAt);
        }

        $content= view('comics.rand_app')->withArticles($rand_articles);
        \fcache::make()->save($content)->costTime();
        return $content;
    }
    public function category($id, $name = "")
    {
        $expiresAt = Carbon::now()->addMinutes(5);
        $categories = Category::comic();
        if (!array_key_exists($id, $categories->toArray())) abort(404, "文章類別不存在");
        $agent = new Agent();
        //1.手機專屬 2.桌機上方 3.正方形


        $cate_cache = 'comic_cate_articles_' . $id . "_" . Input::get('paged');
        if (cache_has($cate_cache)) {
            $cate_articles = cache_get($cate_cache);
        } else {
            $cate_articles = Article::publish()->comic()->with('ez_map')->with('tagged')->where('category_id', $id)->orderBy('publish_at', 'desc')->paginate(10, ['*'], 'paged');
            cache_put($cate_cache, $cate_articles, $expiresAt);
        }
        $cate_articles->setPath(''); //重要
        $share_url = rawurldecode(route('comics.category', ['id' => $id, 'name' => $name]));

        $page = ['title' => 'AVBody', 'sub_title' => $name,
            'url' => '', 'share_url' => $share_url, 'photo' => $cate_articles[0]->photo];
        $tmp_cache = 'comic_rand_articles_' . $id;
        if (cache_has($tmp_cache)) {
            $rand_articles = cache_get($tmp_cache);
        } else {
            $rand_articles = Article::publish()->comic()->with('ez_map')->orderByRaw("RAND()")->take(10)->get();
            cache_put($tmp_cache, $rand_articles, $expiresAt);
        }

        return view('comics.category')
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
        $page = ['title' => 'ENL暗黑網', 'sub_title' => 'AVBody',
            'url' => url(''), 'share_url' => url(''), 'share_url' => url(''), 'photo' => asset('image/comic_logo.png')
        ];// $rand_articles =

        return view('comics.login')
            ->with('plan', 1)
            ->with('mobile', $agent->isMobile())
            ->withPage($page);//->with('categories', $categories);
    }
    public function tag($name)
    {
        $expiresAt = Carbon::now()->addMinutes(5);
        $categories = Category::comic();
        //記憶all選單
        $my_tags = Article::existingTags()->pluck('name')->all();
        //dd($my_tags);
        if (!in_array($name, $my_tags)) abort(404, "文章tag不存在");

        //if ($categories[$id] != $name) return redirect(route('articles.category', ['id' => $id, 'name' => $categories[$id]]));
        $tmp_cache = 'tag_comics_' . $name . "_" . Input::get('paged');
        if (cache_has($tmp_cache)) {
            $tag_articles = cache_get($tmp_cache);
        } else {
            $tag_articles = Article::publish()->comic()->with('ez_map')->with('tagged')->withAnyTag([$name])->orderBy('publish_at', 'desc')->paginate(10, ['*'], 'paged');
            cache_put($tmp_cache, $tag_articles, $expiresAt);
        }

        $tag_articles->setPath(''); //重要
        if ($tag_articles->count() == 0) abort(404, "文章tag不存在");

        $share_url = rawurldecode(route('comics.tag', ['name' => $name]));

        $page = ['title' => 'AVBody', 'sub_title' => $name,
            'url' => '', 'share_url' => $share_url, 'photo' => $tag_articles[0]->photo];
        $tmp_cache='comic_rand_articles_' . $name;
        if (cache_has($tmp_cache)) {
            $rand_articles = cache_get($tmp_cache);
        } else {
            $rand_articles = Article::publish()->comic()->with('ez_map')->orderByRaw("RAND()")->take(10)->get();
            cache_put($tmp_cache, $rand_articles, $expiresAt);
        }


        //   dd( $tag_articles);
        return view('comics.tag')
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
        if (strpos($data['url'], "comic.eznewlife.com")) {
            $refer = $data['url'];
        } else {
            $refer = route("comics.index");
        }
        // echo $refer;
        return redirect($refer);
    }

    public function index()
    {
        //Cookie::queue(Cookie::forget('adult'));

        $categories = Category::comic();
        //   dd($categories);

        $expiresAt = Carbon::now()->addMinutes(5);
        $share_url = rawurldecode(route('comics.index'));
        //  $categories = Category::lists('name', 'id');
        //  Cookie::get('name');
        if (Cookie::get('adult') == 1) {
            //  dd("adult");
        } else {
            //  dd("child");
        }
        $tmp_cache = 'comic_index_articles_15_' . Input::get('page');
        if (cache_has($tmp_cache)) {
            $articles = cache_get($tmp_cache);
        } else {
            $articles = Article::publish()->comic()->with('author', 'ez_map')->orderBy('publish_at', 'desc')->paginate(15);
            cache_put($tmp_cache, $articles, $expiresAt);
        }
      //  dd($articles->currentPage());

        /*
        $tmp_cache = 'comic_rand_articles_3';
        if (cache_has($tmp_cache)) {
            $rand_articles = cache_get($tmp_cache);
        } else {
            $rand_articles = Article::publish()->comic()->with('author', 'ez_map')->orderByRaw("RAND()")->take(3)->get();
            cache_put($tmp_cache, $rand_articles, $expiresAt);
        }*/
        $agent = new Agent();
        //1.手機專屬 2.桌機上方 3.正方形

        $page = ['title' => 'H-Comic', 'sub_title' => '',
            'url' => url(''), 'share_url' => url(''), 'share_url' => url(''), 'photo' => $articles[0]->photo];       // $rand_articles =

        return view('comics.index')->with('articles', $articles)
          //  ->with('rand_articles', $rand_articles)
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
    {
        $categories = Category::comic();

        $data = Input::all();
        // $article_id = $this->id_to_article_id($id);
        $expiresAt = Carbon::now()->addMinutes(5);
        $parameter = Parameter::comic()->lists('data', 'name');


        if (Input::has('q') and !empty(Input::get('q'))) {
            $q = Input::get('q');
        } else {
            return redirect(url("/"));
        }
        $share_url = url("/");
        $page = ['title' => 'AVBody', 'sub_title' => $q,
            'url' => '', 'share_url' => $share_url, 'photo' => ''];

        $articles = Article::publish()->comic()->with('author', 'ez_map')
            ->whereRaw('(title LIKE "%' . $q . '%")')->orderBy('publish_at', 'desc')->paginate(15);

        $articles->setPath(''); //重要
        // $articles =$articles->toArray();


        $agent = new Agent();
        return view('comics.search')->with('articles', $articles)
            ->with('plan', 2)
            ->with('q', $q)
            ->with('mobile', $agent->isMobile())
            ->with('categories', $categories)
            ->withPage($page);//->with('categories', $categories);

    }

    public function show($id, $title = "")
    {
        $categories = Category::comic();

        /*
        $tmp_cache='comic_parameter';
        if (cache_has($tmp_cache)) {
            $parameter = cache_get($tmp_cache);
        }else {
            $parameter = Parameter::comic()->lists('data', 'name');
            cache_forever($tmp_cache,  $parameter);
            // cache_put($tmp_cache, $article, $expiresAt);
        }*/
        $parameter = Parameter::comic()->lists('data', 'name');
        // dd($parameter);
        $data = Input::all();
        // $article_id = $this->id_to_article_id($id);
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


        // $categories = Category::lists('name', 'id');
        // return Helper::prettyJson(['asdf' => 'qwerty'], 200);
        $share_url = route('comics.show', ['id' => $article->ez_map[0]->unique_id]);

        $page = ['title' => $article->category->name, 'sub_title' => $article->title,
            'url' => '', 'share_url' => $share_url, 'photo' => $article->photo];
        // $prev = Article::publish()->comic()->where('publish_at', '>', $article->publish_at)->orderBy('publish_at', 'desc')->first();
        // $next =Article::publish()->comic()->where('publish_at', '<', $article->publish_at)->orderBy('publish_at', 'desc')->first();

        //   $prev_link = $this->previous_link($article->id);
        // $next_link = $this->next_link($article->id);

        // $prev_title = $this->previous_title($article->id);
        // $next_title = $this->next_title($article->id);
        $plan = 1;
        if ($article->flag === 'P') $plan = 2;
        $tmp_cache = 'comic_rand_articles_6_' . $id;
        if (cache_has($tmp_cache)) {
            $rand_articles = cache_get($tmp_cache);
        } else {
            $rand_articles = Article::publish()->comic()->with('author', 'ez_map')->orderByRaw("RAND()")->take(6)->get();
            cache_put($tmp_cache, $rand_articles, $expiresAt);
        }
        // dd($rand_articles);
        //echo 'aaa';print_r($prev);
        $agent = new Agent();
        //1.手機專屬 2.桌機上方
        //dd($parameter);

        $article->content =article_comic_content($article->content);
        //  $article->content= preg_replace("#(<img[^>]+)src=([^>]+>)#", '$1 data-original= $2', $article->content);
        //分頁處理
        $article = content_paging($article);
        //$ad=get_ad(12,1,'getez.info');

        //dd($ad->code);


     // dd($parameter);



        $content= view('comics.show')->withArticle($article)
            ->with('rand_articles', $rand_articles)
            ->with('plan', $plan)
            ->with('parameter', $parameter)
            ->with('categories', $categories)
            ->with('mobile', $agent->isMobile())
            ->withPage($page);

        \fcache::make()->save($content)->costTime();
        return $content;
        //->withPrev($prev)->withNext($next);
        // ->with('prev_link', $prev_link)
        // ->with('next_link', $next_link)
        // ->with('prev_title', $prev_title)
        // ->with('next_title', $next_title)
        //  ->with('categories', $categories);
    }


}

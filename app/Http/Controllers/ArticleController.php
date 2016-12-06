<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Article, App\Category, App\User, App\EnlUser,App\EnlUserCollect,App\ArticleMap;
use Auth, Input;
use Carbon\Carbon;
use DB;
use ff;
use Cache;
use File;
use Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleController extends Controller
{

    public function index()
    {
        $url=url("/") ;

        $parameter =cache_get('enl_parameters');
        /** 自帶文章 id 導到文章頁
         * index?p=x
         * index?paged=xx
         */

        if (Input::has('p')) {
            $next=cache_get('article_map_' . Input::get('p'));
            $link=$this->_spell_link(Input::get('p'),hyphenize($next->title));
            return redirect($link);

        } else if (Input::has('cat')) {
            /*** 取得類別資料**/
            $categories=$this->get_category();
            $query = '';
            if (Input::has('paged')) {
                $query = '?paged=' . Input::get('paged');
            }
             return redirect(route('articles.category', ['id' => Input::get('cat'), 'name' => $categories[Input::get('cat')]->name]) . $query);
        }

        $paged = 1;
        if (Input::has('paged')) $paged = Input::get('paged');
        $paged=(isset($paged) and intval($paged)>1)?intval($paged):1;


        if (ff\c('device')->is_mobile() == 1) {
            //return 'mobile';
            $r = $this->get_index_data_m($paged);
            return ff\mobile_index($r, $this, 'is_home');

        }

        $r = $this->get_index_data();

        $content = view('articles.index')
            ->with('expert_articles', $r->expert_articles)
            ->with('other_articles', $r->other_articles)
            ->with('rand_articles', $r->rand_articles)
            ->with('paged', $paged)
            ->with('plan', $r->plan)
            ->withPage($r->page)->with('categories', $r->categories);

       // \fcache::make()->save($content)->costTime();

        $content = replace_eznewlife($content);
        
        \fcache::make()->save($content)->costTime();

        return $content;


    }
    public function show($id, $title = "")
    {

        $data = Input::all();
        $expiresAt = Carbon::now()->addMinutes(5);

        /**讀取文章頁面 cache**/
        $tmp_cache='article_map_' . $id;
      //  cache_forget($tmp_cache);
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
        if (\App::environment() == 'production') {
            $share_url ="https://" .\Request::server('HTTP_HOST') . '/' . $id . '/' . hyphenize($article->title);
        }else {
            $share_url ='/' . $id . '/' . hyphenize($article->title);

        }
       // dd(\App::environment());

        $right_title = hyphenize($article->title);
        if ($right_title != $title) {
                return redirect($share_url);

        }
       // redirect()->secure("/")
      //  dd($_SERVER);
       // dd(Request::url());
/*
       $right_title = hyphenize($article->title);
       if ($right_title != $title) {
           return redirect($share_url);
       }*/
        //$article->content=implode("@enl@",$article->content_pages);
	//dd($article);
	   if (Input::get('page')>1){
           $article->content=implode("@enl@",$article->content_pages);
           $article=content_paging($article);
           $article->page=Input::get('page');
		  //$article->content_current=$article->content=$article->content_pages[Input::get('page')-1];
		//  $article->page=Input::get('page');
	   }
        //$article->amp_content=article_amp_content($article->content);


        $article->content=replace_alt($article->content,$article->title);
        //一個網頁 只能有一個h1 所以，其它的h1 改成h2
        $article->content=str_ireplace('<h1','<h2',  $article->content);
        $article->content=str_ireplace('</h1>','</h2>',  $article->content);

        if ($article->type!='enl') {

            abort(404, "文章不存在");
        }
        if (!$article) abort(404, "文章不存在");
        /**上下頁*/
         $prev_link = $next_link = $prev_title =$next_title=null ;

        if (cache_has('unique_ids')) {
            $unique_ids= cache_get('unique_ids');
        } else {
            save_cate_cache($article->category_id);
            $unique_ids= cache_get('unique_ids');
        }

        $unique_ids=$unique_ids[$article->category_id];
        $current_key=array_search($id, $unique_ids);
       // echo $current_key;exit;
        if ($current_key) {
            $prev_id = isset($unique_ids[$current_key - 1]) ? $unique_ids[$current_key - 1] : null;
            $next_id = isset($unique_ids[$current_key + 1]) ? $unique_ids[$current_key + 1] : null;
            if ($prev_id) {
                $prev = cache_get('article_map_' . $prev_id);
                $article->prev_link = $prev_link = $this->_spell_link($prev_id, hyphenize($prev->title));
                $article->prev_title = $prev_title = $prev->title;
            }
            if ($next_id) {
                $next = cache_get('article_map_' . $next_id);
                $article->next_link = $next_link = $this->_spell_link($next_id, hyphenize($next->title));
                $article->next_title = $next_title = $next->title;
            }
        } else {
            $next_link=$prev_link=null;
        }
      //  dd($article);
        //-----------
        /*** 取得類別資料**/
        $categories= $article->categories=$this->get_category();
        /**到手機版**/
        if (ff\c('device')->is_mobile() == 1) {
            $article->content=implode("@enl@",$article->content_pages);
            return ff\mobile_article($id, $article->id, $right_title, $this,$article);
        }
        /**分頁*/
        //$article= content_paging($article);
        /*** 取得收藏資料**/
        $collects=enl_user_info();


        $page = ['title' => $article->category->name, 'sub_title' => $article->title,
            'url' => '', 'share_url' => $share_url, 'photo' => $article->photo];

        $plan = $article->plan;
        /** * 亂數 */
        $rand_articles=$this->get_rand_tag_data(10,$article);
        /**iso时间**/
        $dt = Carbon::parse($article->publish_at);
        $article->publish_at_iso=$dt->toIso8601String();
        $dt = Carbon::parse($article->updated_at);
        $article->updated_at_iso=$dt->toIso8601String();
        $dt = Carbon::parse($article->created_at);
        $article->created_at_iso=$dt->toIso8601String();
        /*** 取得參數資料**/
       // dd($article->tags->pluck('name')->all());
        $parameter =cache_get('enl_parameters');

        $uid = \unique_id(10);

        $content = view('articles.show')->withArticle($article)
            ->with('rand_articles', $rand_articles)
            ->withPage($page)
            ->with('prev_link', $prev_link)
            ->with('next_link', $next_link)
            ->with('prev_title', $prev_title)
            ->with('next_title', $next_title)
            ->with('plan', $plan)
            ->with('collects', $collects)
            ->with('parameter',$parameter)
            ->with('category_id',$article->category_id)
            ->with('categories', $categories)
            ->with('uid', $uid);

        //if ( Auth::enl_user()->check()===false )
        // \fcache::make()->save($content)->costTime();

        /*
        $dir = __APP__.'storage/frank/';
        if( !file_exists($dir) ){
            mkdir($dir, 0777);
        }
        */

        //file_put_contents( __APP__.'storage/frank/'.$uid , $content);

        //echo '<!--'.__APP__.'storage/frank/'.$uid.'-->';

        $content = replace_eznewlife($content);
        
        \fcache::make()->save($content)->costTime();

        return $content;


    }
    public function collect($id)
    {
        if (Auth::enl_user()->check()==false) return [];
        $data = Input::all();

        $collect=new EnlUserCollect();
        $collect->setConnection('master');
        //dd($a);
        //$tmp_cache='enl_user_collect_' . Auth::enl_user()->get()->id;
       // cache_forget($tmp_cache);
        $enl_user_id=Auth::enl_user()->get()->id;
        $collect=$collect->where('article_id',$id)->where('enl_user_id',$enl_user_id);
        //dd($collect->writConnection());
        //	//$collect->connection('write');
        // dd($collect);

        if ($collect->count()==0){
            $collect=new EnlUserCollect();
            $collect->setConnection('master');
            $collect->enl_user_id=$enl_user_id;
            $collect->article_id=$id;
            $collect->save();
            $message = " 已收藏";
            $status=1;
        } else {
            //$collect->connection('write');
            $collect->delete();
            $message = " 已取消收藏";
            $status=0;
        }
       // set_enl_user_info();
        $response=['message'=>$id.$message,'status'=>$status];
        return Response::json($response);


    }
    public function notfound()
    {
        // return view('articles/404')->with('plan', 1);
        if (ff\c('device')->is_mobile() == 1) {

            $type="application/xml";
            $xml='<Error>
                <Code>WebDenied</Code>
                <Message>Web Denied</Message>
                </Error>';
            return response($xml)
                ->header('Content-Type', $type);

        } else {
            return view('articles/404')->with('plan', 1);
        }


    }

    public function ajax($page = ""){
        $other_articles = $this->getDataByPage();
        // return response()->json($other_articles);
        return view('articles.ajax')
            ->with('plan', 1)
            ->with('other_articles', $other_articles);

    }

    public function log_url($url)
    {
        return;
        $file = __DIR__ . '/../../../storage/count/redirect_url.txt';
        $url = $url . '-' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "\n";
        file_put_contents($file, $url, FILE_APPEND);
    }

    public function getDataByPage()
    {
        $enl=cache_get('enl_ids');
        $expert=cache_get('expert_ids');
        $count=count($enl)-5;
        $filter_ids = array_slice( $expert, -5, 5);
        $limit=get_limit($count,Input::get('paged',1),4);
        $other_ids=array_slice(array_diff($enl, $filter_ids),-4*Input::get('paged',1),$limit);
        $other_articles=[];
        foreach ($other_ids as $k=>$other_id){
            if (cache_has('article_' . $other_id)) {
                $other_articles[$k]= cache_get('article_' . $other_id);
            }
        }
        $count=count($enl)-5;
        $other_articles=$this->paginate(array_reverse($other_articles), 4,$count);
       // dd($other_articles);
        return  $other_articles;
    }


    public function pageData($paged)
    {
        $data = $this->getDataByPage();
    }

    protected function get_normal_rand_data($count){
        /**
         * 亂數
         */
        $rand=cache_get('enl_ids');
        $rand_article_ids = array_rand($rand,$count*2);
        $i=0;
        $rand_articles=[];
        foreach ($rand_article_ids as $k=>$rand_key){
            $rand_id=$rand[$rand_key];
            if (cache_has('article_' . $rand_id)) {
                $tmp=cache_get('article_' . $rand_id);
                if ($tmp->flag=="G") {
                    $rand_articles[$i] = cache_get('article_' . $rand_id);
                }
                $i++;
            }
            if ($i>=$count) break;
        }
        return $rand_articles;
    }

    protected function get_rss_rand_data($count){
        /**
         * 亂數
         */
        $rand=cache_get('enl_ids');
        $rand_article_ids = array_rand($rand,$count*2);
        $i=0;
        $rand_articles=[];
        foreach ($rand_article_ids as $k=>$rand_key){
            $rand_id=$rand[$rand_key];
            if (cache_has('article_' . $rand_id)) {
                $tmp= cache_get('article_' . $rand_id);
            //    dd($tmp);
                if ($tmp->plan=="1") {
                    $rand_articles[$i] = $tmp;
                    $i++;
                }
            }
            if ($i>=$count) break;
        }
        return $rand_articles;
    }
    protected function get_rand_data($count){
        /**
         * 亂數
         */
        $rand=cache_get('enl_ids');
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
    protected function get_rand_tag_data($count,$article){
        /**
         * 亂數
         */
        $rand_articles=$tmp_rand=array();
        $tags=$article->tags->pluck('name')->all();
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
                        $j++;
                        $rand_articles[$j] = cache_get('article_' . $tag_id);
                        $tmp_array[]=$rand_articles[$j]->id;

                    }
                    if ($j >= $count) break;
                }
            }
        }
        $insufficient=$count-$j;
      //  dd($insufficient);
        if ($insufficient>0){
            $tmp_rand=$this->get_rand_data($insufficient);
        }
       // dd([$tmp_rand,$insufficient,$rand_articles]);
        //if (count($rand_articles)>0) {
            $rand_articles = array_merge($rand_articles, $tmp_rand);


        return $rand_articles;
    }
    public function get_category()
    {
        /*** 取得類別資料**/
        $tmp_cache='enl_categories';
        if (cache_has($tmp_cache)) {
            $categories  = cache_get($tmp_cache);
        } else {
            $categories= Category::getList();
            cache_forever($tmp_cache,  $categories);
        }
        return $categories;
    }
    public function get_category_desc()
    {
        /*** 取得類別資料**/
        $tmp_cache='enl_categories_desc';
        if (cache_has($tmp_cache)) {
            $categories  = cache_get($tmp_cache);
        } else {
            $categories= Category::enlDesc();
            cache_forever($tmp_cache,  $categories);
        }
        return $categories;
    }
    protected function get_index_data()
    {
        $conn = ff\conn();
        $r = new \stdClass();
        /** * 取第一筆資料**/
        $index=cache_get('enl_ids');
        $index_id =  implode(array_slice( $index, -1, 1));


        if (cache_has('article_' . $index_id)) {
            $article= cache_get('article_' . $index_id);
        }

        $r->page = ['title' => 'EzNewlife', 'sub_title' => 'EzNewlife-簡單新生活',
            'url' => url(''), 'share_url' => url(''), 'photo' => $article->photo];
        $r->share_url = rawurldecode(route('articles.index'));

        /*** 取得類別資料**/
        $r->categories=$this->get_category();
        /**取得enl expert(類別為1,2)的五筆文章*/
        $expert=cache_get('expert_ids');
        
       // dd($expert);
        $expert_article_ids = array_slice( $expert, -5, 5);
        $i=0;
        $r->expert_articles=[];
        $filter_ids = [];
        foreach ($expert_article_ids as $k=>$expert_id){
            if (cache_has('article_' . $expert_id)) {
                $r->expert_articles[$i]= cache_get('article_' . $expert_id);
                $filter_ids[]=$expert_id;
                $i++;
            }
        }
        $r->expert_articles=array_reverse($r->expert_articles);
        /**取得filter ids 以外的enl 文章*/
        $enl=cache_get('enl_ids');
        //dd($enl);
        $other_ids=array_slice(array_diff($enl, $filter_ids),'-4',4);
        $r->other_articles=[];
        foreach ($other_ids as $k=>$other_id){
            if (cache_has('article_' . $other_id)) {
                $r->other_articles[$k]= cache_get('article_' . $other_id);
            }
        }

        $r->other_articles=array_reverse($r->other_articles);
        /*** 亂數***/
        $r->rand_articles=$this->get_rand_data(10);
        $r->plan = 1;
        return $r;
    }



    protected function get_index_data_m($paged, $category_id = null)
    {
        $conn = ff\conn();
        $r = new \stdClass();
        $paged=(isset($paged) and intval($paged)>1)?intval($paged):1;
        /** * 取第一筆資料**/
        $index=cache_get('enl_ids');
        $index_id =  array_slice( $index, -1, 1);
        if (cache_has('article_' . $index_id)) {
            $article= cache_get('article_' . $index_id);
        }
        $r->page = ['title' => 'EzNewlife', 'sub_title' => '簡單新生活',
            'url' => url(''), 'share_url' => url(''), 'photo' => $article->photo];
        $r->share_url = rawurldecode(route('articles.index'));
        $r->categories = $this->get_category();
        /**取得enl expert(類別為1,2)的五筆文章*/
        $expert=cache_get('expert_ids');
        $expert_article_ids = array_slice( $expert, -5, 5);
        $i=0;
        $r->expert_articles=[];
        foreach ($expert_article_ids as $k=>$expert_id){
            if (cache_has('article_' . $expert_id)) {
                $r->expert_articles[$i]= cache_get('article_' . $expert_id);
                $i++;
            }
        }
        $r->expert_articles=array_reverse($r->expert_articles);

        if ($category_id === null) {
            /**取得filter ids 以外的enl 文章*/
            $filter_ids = [];
            foreach ($r->expert_articles as $k => $a) {
                $filter_ids[] = $a->id;
            }
            $filter_ids = [];
            $enl=cache_get('enl_ids');

            $count=count(array_diff($enl, $filter_ids));

            $limit=get_limit($count,$paged,10);
            $other_ids=array_slice(array_diff($enl, $filter_ids),(-10*$paged),$limit);
            $r->other_articles=[];
            foreach ($other_ids as $k=>$other_id){
                if (cache_has('article_' . $other_id)) {
                    $r->other_articles[$k]= cache_get('article_' . $other_id);
                }

            }
            $r->other_articles=array_reverse($r->other_articles);
            $r->maxpaged = ceil($count/10);
            $paged=(isset($paged) and intval($paged)>1)?intval($paged):1;
            $r->paged=($r->maxpaged<$paged )?$r->maxpaged:intval($paged);
        } else {
            /**取得類別文章*/
            $ids=cache_get('ids');
            $limit=get_limit(count($ids[$category_id]),$paged,10);
            $other_ids=array_slice($ids[$category_id],-10*$paged,$limit);
            $other_articles=[];
            foreach ($other_ids as $k=>$cate_id){
                if (cache_has('article_' . $cate_id)) {
                    $other_articles[$k]= cache_get('article_' . $cate_id);
                }
            }
            $r->other_articles=$this->paginate(array_reverse($other_articles), 10,count($ids[$category_id]));
            $r->maxpaged = ceil(count($ids[$category_id])/10);
            $paged=(isset($paged) and intval($paged)>1)?intval($paged):1;
            $r->paged=($r->maxpaged<$paged )?$r->maxpaged:intval($paged);
        }
        /*** 亂數***/
        $r->rand_articles=$this->get_rand_data(10);


        $r->plan = 1;
        return $r;
    }


    public function category($id, $name = "")
    {
        $expiresAt = Carbon::now()->addMinutes(5);
        /** * 取得類別資料**/
        $categories=$this->get_category();
        $categories_desc=$this->get_category_desc();
        //dd($categories);
        if (!array_key_exists($id, $categories->toArray())) abort(404, "文章類別不存在");

        if ($categories[$id] != $name) {
            return redirect(route('articles.category', ['id' => $id, 'name' => $categories[$id]]));
        }

        /**手機版*/
        if (ff\c('device')->is_mobile() == 1) {

            if (Input::has('paged')) $paged = Input::get('paged');


            $r = $this->get_index_data_m($paged, $id);
            $r->category_id = $id;
            return ff\mobile_index($r, $this, 'is_category');

        }
        /**取得類別文章*/
        $ids=cache_get('ids');
        $limit=get_limit(count($ids[$id]),Input::get('paged',1),10);
        $cate_ids=array_slice($ids[$id],-10*Input::get('paged',1),$limit);
        //dd([$cate_ids,$ids[$id]],-10*Input::get('paged',1));
        $cate_articles=[];
        foreach ($cate_ids as $k=>$cate_id){
            if (cache_has('article_' . $cate_id)) {
                $cate_articles[$k]= cache_get('article_' . $cate_id);
            }
        }
        $cate_articles=$this->paginate(array_reverse($cate_articles), 10,count($ids[$id]));
        $cate_articles->setPath(''); //重要
//dd($cate_articles);
        $share_url = rawurldecode(route('articles.category', ['id' => $id, 'name' => $name]));
        $page = ['title' => 'EzNewLife', 'sub_title' => $name  .'-EzNewLife','description'=>$categories_desc[$id],
            'url' => '', 'share_url' => $share_url, 'photo' => $cate_articles[0]->photo];

        /**亂數**/
        $rand_articles=$this->get_rand_data(10);
        return view('articles.category')
            ->with('rand_articles', $rand_articles)
            ->with('cate_articles', $cate_articles)
            ->with('category_id', $id)
            ->with('plan', 1)
            ->withPage($page)->with('categories', $categories);
    }

    public function paginate($items,$perPage,$count)
    {

        return new LengthAwarePaginator($items, $count, $perPage,Paginator::resolveCurrentPage('paged'),
            array('path' => Paginator::resolveCurrentPath(),
                  'pageName' => 'paged'));
    }


    public function tag($name)
    {
        $expiresAt = Carbon::now()->addMinutes(5);

        /** * 取得類別資料**/
        $categories=$this->get_category();
        /**取得tag文章*/
        $tmp_cache = 'enl_tag_ids_' . $name;
        $tag_ids = cache_get($tmp_cache);

        if (count($tag_ids) == 0) abort(404, "文章tag不存在");

        $limit=get_limit(count($tag_ids),Input::get('paged',1),10);
        $cate_ids_data=array_slice($tag_ids,-10*Input::get('paged',1),$limit);
        $tag_articles=[];
        foreach ($cate_ids_data as $k=>$tag_id){
            if (cache_has('article_' . $tag_id)) {
                $tag_articles[$k]= cache_get('article_' . $tag_id);
            }
        }


        $tag_articles=$this->paginate(array_reverse($tag_articles), 10,count($tag_ids));

        $tag_articles->setPath(''); //重要

        $share_url = rawurldecode(route('articles.tag', ['name' => $name]));

        $page = ['title' => 'EzNewLife', 'sub_title' => $name.'-EzNewLife',
            'url' => '', 'share_url' => $share_url, 'photo' => $tag_articles[0]->photo];
        /**亂數**/
        $rand_articles=$this->get_rand_data(10);

        //   dd( $tag_articles);
        return view('articles.tag')
            ->with('rand_articles', $rand_articles)
            ->with('tag_articles', $tag_articles)
            ->with('plan', 1)
            ->with('name', $name)
            ->withPage($page)->with('categories', $categories);
    }

    public function search()
    {
        $expiresAt = Carbon::now()->addMinutes(5);
        /** * 取得類別資料**/
        $categories=$this->get_category();


        if (Input::has('q') and !empty(Input::get('q'))) {
            $q = Input::get('q');
        } else {
            return redirect(url("/"));
        }
        $share_url = url("/");
        $page = ['title' => 'EzNewLife', 'sub_title' => "搜尋「".$q.'」-EzNewLife',
            'url' => '', 'share_url' => $share_url, 'photo' => ''];

        $search_ids = Article::publish()->enl()
            ->where(function ($query) {
                $query->where('title', 'like', '%' . Input::get('q') . '%')
                    ->orwhere('content','like', '%' . Input::get('q') . '%');
            })
            ->orderBy('publish_at', 'desc')->lists('id')
            ->toArray();


        $search_ids_data=array_slice($search_ids ,10*(Input::get('paged',1)-1),10);
        $articles=[];
        foreach ($search_ids_data as $k=>$search_id){
            if (cache_has('article_' . $search_id)) {
                $articles[$k]= cache_get('article_' . $search_id);
            }
        }
        $articles=$this->paginate(array_reverse($articles), 10,count($search_ids));

        $articles->setPath(''); //重要
        // $articles =$articles->toArray();
        /**亂數**/
        $rand_articles=$this->get_rand_data(10);

        return view('articles.search')
            ->with('articles', $articles)
            ->with('rand_articles', $rand_articles)
            ->with('plan', 1)
            ->with('q', $q)
            ->withPage($page)->with('categories', $categories);
    }



    public function googlesearch(){
        $expiresAt = Carbon::now()->addMinutes(5);
        /** * 取得類別資料**/
        $categories=$this->get_category();


        if (Input::has('q') and !empty(Input::get('q'))) {
            $q = Input::get('q');
        } else {
            return redirect(url("/"));
        }
        $share_url = url("/");
        $page = ['title' => 'EzNewLife', 'sub_title' => $q,
            'url' => '', 'share_url' => $share_url, 'photo' => ''];

        /**亂數**/
        $rand_articles=$this->get_rand_data(10);

        return view('articles.googlesearch')
            ->with('rand_articles', $rand_articles)
            ->with('plan', 1)
            ->with('q', $q)
            ->withPage($page)->with('categories', $categories);
    }


    public function test()
    {

        $r = new stdClass;

        $r->a=1;
        $r->b=2;

        cache_forever('franktest', $r);

        $a = cache_get('franktest');

        var_dump($a);

        return '';

        
        $ts = microtime(true);
        
        for($i=0; $i<1 ;$i++) cache_get('enl_ids');
        $te = microtime(true);
        echo 'cache 檔案 的速度 :'.($te-$ts)."<br>";

        $data = cache_get('enl_ids');

        //print_r($r);

        $file = __DIR__.'/../../../storage/test';
        
        file_put_contents($file, json_encode($data));


        $ts = microtime(true);
        for($i=0; $i<1 ;$i++){
            $r = file_get_contents($file);
            $r = json_decode($r);
        }
        $te = microtime(true);
        echo 'json 檔案 的速度 :'.($te-$ts)."<br>";
        


        return '';
        return view('articles.test');

    }
    public function instant($id){


        $expiresAt = Carbon::now()->addMinutes(5);
        if (cache_has('article_' . $id)) {
            $article = cache_get('article_' . $id);
            $article_id = $article->id;
        } else {
            $article_id = $this->id_to_article_id($id);
            $article = Article::with('author', 'ez_map', 'category', 'tagged')->find($article_id);
            cache_put('article_' . $id, $article, $expiresAt);

        }
        //$a= $this->get_article_data( $article_id);dd($a);
        $article->fb_publish_at = Carbon::parse($article->publish_at)->toIso8601String();
        $article->fb_updated_at = Carbon::parse($article->updated_at)->toIso8601String();



        $right_title = hyphenize($article->title);
        $article->share_url =  'http://eznewlife.com'. '/' . $id . '/' . $right_title;

        $article->content=article_instant_content($article->content);

        //$content= preg_replace("#(<img[^>]+)src=([^>]+>)#", '$1 data-original= $2', $content);
        // $article->content= preg_replace('#(<a [^>]+)(<img [^>]+)#', '<figure data-feedback="fb:likes, fb:comments">'.'$1 $2'.'></figure',$article->content);
        /* $article->content = preg_replace('~(<p)?.*?><a .*?>\s*<img .*?>\s*</a>(</p>)?~i','<figure data-feedback="fb:likes, fb:comments">$0</figure>', $article->content);*/
        /*  $article->content = preg_replace('~<a .*?>\s*<img .*?>\s*</a>~i','<figure data-feedback="fb:likes, fb:comments">$0</figure>', $article->content);*/
        //參考文章

        $cate_cache = 'instant_refer_' . $id . "_" ;
        if (cache_has($cate_cache)) {
            $cate_articles = cache_get($cate_cache);
        } else {
            $cate_articles = Article::publish()->with('ez_map')->with('tagged')->where('category_id', $article->category_id)->where('id', '!=',$article->id)->orderByRaw("RAND()")->take(3)->get();
            cache_put($cate_cache, $cate_articles, $expiresAt);
        }


        $content=view('articles.instant')->withArticle($article)->with('foot_articles', $cate_articles);
        $content = replace_eznewlife($content);

        // $content =str_replace("http://getez.info/focus_photo","http://demo.getez.info/focus_photo",$content);
        $tidy = tidy_parse_string($content);

        $body = $tidy->Body();
        echo $body->value;
        pp($content);
        return $content;

    }
    public function overview(){
        $articles = Article::publish()->enl()->with('ez_map')->where('flag','G')->orderBy("publish_at",'desc')->take(200)->get();
        foreach ($articles as $k=>$article) {
            $instant_url = route("articles.instant", $article->ez_map[0]->unique_id);
            $show_url = route("articles.show", $article->ez_map[0]->unique_id,hyphenize($article->title));
            // echo $show_url;
            echo "<a href='$show_url' target='_blank'>" . ($k+1) .'</a>'
                ." <a href='$instant_url' target='_blank'>" . $article->title . "</a><br>";
        }


    }
    public function rss()
    {    /** * 取得類別資料**/
        $categories=$this->get_category();

        $expiresAt = Carbon::now()->addMinutes(5);
        $articles = Article::publish()->enl()->with('ez_map')->where('instant',1)->where('flag','G')->orderBy("updated_at",'desc')->take(50)->get();
        $rss=new \stdClass();
        //$a= $this->get_article_data( $article_id);dd($a);
        $rss->fb_publish_at = Carbon::now()->toIso8601String();
        $rss->fb_updated_at =Carbon::now()->toIso8601String();
        // $right_title = hyphenize($article->title);
        //$article->share_url = $this->_spell_link($id, $right_title);
        //  dd($article->share_url);
        $author=User::lists('name','id');
        $cate_cache = 'instant_refer_'  . "_" ;
        if (cache_has($cate_cache)) {
            $cate_articles = cache_get($cate_cache);
        } else {
            $cate_articles = Article::publish()->enl()->with('ez_map')->where('flag','G')->orderByRaw("RAND()")->take(3)->get();
            cache_put($cate_cache, $cate_articles, $expiresAt);
        }
        $cate_articles=$this->get_rss_rand_data(3);
        $content =  view('articles.rss')->withArticles($articles)->withRss($rss)->with('foot_articles', $cate_articles)
        ->with('author',$author)
        ->with('categories', $categories);
        $content = replace_eznewlife($content);

        //\fcache::make()->save($content)->costTime();

        return $content;
    }


    /*-iframe 小窗格*/
    public function rand()
    {

        $rand_articles=$this->get_rand_data(3);
        $content = view('articles.rand')->withArticles($rand_articles);
        //\fcache::make()->save($content)->costTime();
        return $content;
    }

    public function rand_app()
    {
        $rand_articles=$this->get_rand_data(3);
        $content = view('articles.rand_app')->withArticles($rand_articles);
       // \fcache::make()->save($content)->costTime();
        return $content;
    }


    public function current_page_url_KILL($article_id)
    {

        $article = $this->get_article_data($article_id);

        $id = $article->id;
        $title = $article->title;

        $id = $this->article_id_to_id($id);

        /*
        if (!empty($article->refer_url)) {
            $share_url = rawurldecode($article->refer_url);
        } else {
            $share_url = rawurldecode(route('articles.show', ['id' => $id, 'title' => $title]));
        }
        */
        $share_url = rawurldecode(route('articles.show', ['id' => $id, 'title' => $title]));

        return $share_url;
    }

    /**
     * 把後面連結拼出來
     **/
    public function spell_link($article_id, $title)
    {
        $id = $this->article_id_to_id($article_id);
        $right_title = hyphenize($title);

        return $this->_spell_link($id, $right_title);

        // return rawurldecode(route('articles.show', ['id' => $id, 'title' => $right_title]));
        //return "/$id/$title";
    }

    public function _spell_link($id, $right_title)
    {

        if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO']==='https'){
            $scheme = 'https';
        }else if(isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME']==='https'){
            $scheme = 'https';
        }else{
            $scheme = 'http';
        }


        $domain=$scheme.'://'.$_SERVER['HTTP_HOST'];

        $url = $domain . '/' . $id . '/' . $right_title;
        return $url;
    }


    public function spell_link_by_article_id($article_id)
    {
        $article = $this->get_article_data($article_id);
        return $this->spell_link($article->id, $article->title);
    }

    public function previous_link($article_id)
    {
        $article = $this->get_article_data($article_id);

        $prev = Article::publish()->where('category_id', $article->category_id)->where('publish_at', '<', $article->publish_at)->orderBy('publish_at', 'desc')->first();
        //route('articles.show', ['id'=>$prev->id,'title'=>$prev->title])

        if (!$prev) return false;
        return $this->spell_link($prev->id, $prev->title);

    }

    public function next_link($article_id)
    {
        $article = $this->get_article_data($article_id);
        $next = Article::publish()->where('category_id', $article->category_id)->where('publish_at', '>', $article->publish_at)->orderBy('publish_at', 'asc')->first();
        if (!$next) return false;
        return $this->spell_link($next->id, $next->title);

    }

    public function previous_title($article_id)
    {
        $article = $this->get_article_data($article_id);
        $row = Article::publish()->where('category_id', $article->category_id)->where('publish_at', '<', $article->publish_at)->orderBy('publish_at', 'desc')->first();
        if ($row) return $row->title;
        return '';
    }


    public function next_title($article_id)
    {
        $article = $this->get_article_data($article_id);
        $row = Article::publish()->where('category_id', $article->category_id)->where('publish_at', '>', $article->publish_at)->orderBy('publish_at', 'asc')->first();
        if ($row) return $row->title;
        return '';
    }


    public function get_article_data($article_id)
    {
        static $dic = [];

        if (isset($dic[$article_id])) return $dic[$article_id];
        $article=Article::with('author')->with('category')->with('tagged')->find($article_id);

        $dic[$article_id] = $article;

        return $dic[$article_id];
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


    public function article_id_to_id($article_id)
    {
        static $dic = [];

        if (isset($dic[$article_id])) return $dic[$article_id];

        $row = DB::table('articles_map')
            ->where('site_id', ff\config('site_id'))
            ->where('articles_id', $article_id)
            ->select(['unique_id'])
            ->first();

        if (isset($row->unique_id)) {
            $id = $row->unique_id;
        } else {
            $id = 0;
        }
        $dic[$article_id] = $id;
        return $id;
    }

    public function _index()
    {

        /** 自帶文章 id 導到文章頁
         * index?p=x
         * index?paged=xx
         */

        if (Input::has('p')) {
            $article = Article::leftJoin('articles_map', function ($join) {
                $join->on('articles.id', '=', 'articles_map.articles_id')->where('articles_map.site_id', '=', ff\config('site_id'));
            })->where('articles_map.unique_id', '=', Input::get('p'))->firstOrFail();
            //dd($article);
            $right_title = hyphenize($article->title);
            // echo route('articles.show', ['id' => Input::get('p'), 'title' => $right_title])
            // return redirect(route('articles.show', ['id' => Input::get('p'), 'title' => $right_title]));
            $this->log_url($this->_spell_link(Input::get('p'), $right_title));
            return redirect($this->_spell_link(Input::get('p'), $right_title));

        } else if (Input::has('cat')) {
            $category = Category::findOrFail(Input::get('cat'));
            $query = '';
            if (Input::has('paged')) {
                $query = '?paged=' . Input::get('paged');
            }

            $this->log_url(route('articles.category', ['id' => Input::get('cat'), 'name' => $category->name]) . $query);
            return redirect(route('articles.category', ['id' => Input::get('cat'), 'name' => $category->name]) . $query);

        }


        $paged = 1;
        if (Input::has('paged')) $paged = Input::get('paged');


        if (ff\c('device')->is_mobile() == 1) {
            //return 'mobile';
            $r = $this->get_index_data_m($paged);
            return ff\mobile_index($r, $this, 'is_home');

        }

        $r = $this->get_index_data();

        $content = view('articles.index')
            ->with('expert_articles', $r->expert_articles)
            ->with('other_articles', $r->other_articles)
            ->with('rand_articles', $r->rand_articles)
            ->with('paged', $paged)
            ->with('plan', $r->plan)

            ->withPage($r->page)->with('categories', $r->categories);


        \fcache::make()->save($content)->costTime();

        return $content;

    }
    protected function _get_index_data()
    {
        $conn = ff\conn();
        $r = new \stdClass();


        $article = Article::publish()->enl()->orderBy('publish_at', 'desc')->first();
        $r->page = ['title' => 'EzNewlife', 'sub_title' => '簡單新生活',
            'url' => url(''), 'share_url' => url(''), 'photo' => $article->photo];
        $r->share_url = rawurldecode(route('articles.index'));
        $expiresAt = Carbon::now()->addMinutes(5);
        $r->categories = Category::getList();
        $r->expert_articles = $this->get_expert_articles();

        $filter_ids = [];

        foreach ($r->expert_articles as $k => $a) {
            $filter_ids[] = $a->id;
        }

        //$r->expert_articles = Article::publish()->expert()->with('ez_map')->orderBy('publish_at', 'desc')->take(5)->get();

        $r->other_articles = Article::publish()->other($filter_ids)->with('ez_map')->orderBy('publish_at', 'desc')->simplePaginate(4, ['*'], 'paged');


        if (cache_has('rand_articles_index')) {
            $r->rand_articles = cache_get('rand_articles_index');
        } else {
            $r->rand_articles = Article::publish()->enl()->with('ez_map')->orderByRaw("RAND()")->take(10)->get();
            cache_put('rand_articles_index', $r->rand_articles, $expiresAt);
        }

        $r->plan = 1;
        return $r;
    }
    protected function _get_index_data_m($paged, $category_id = null)
    {
        $conn = ff\conn();
        $r = new \stdClass();

        $article = Article::publish()->enl()->orderBy('publish_at', 'desc')->first();
        $r->page = ['title' => 'EzNewlife', 'sub_title' => '簡單新生活',
            'url' => url(''), 'share_url' => url(''), 'photo' => $article->photo];
        $r->share_url = rawurldecode(route('articles.index'));

        $r->categories = Category::getList();

        $r->expert_articles = Article::publish()->expert()->with('ez_map')->orderBy('publish_at', 'desc')->take(5)->get();

        if ($category_id === null) {
            $r->other_articles = Article::paged($paged, 10);
            $r->maxpaged = $this->maxpaged('index', 10);
        } else {
            $r->other_articles = Article::data_category($category_id, $paged, 10);
            $r->maxpaged = $this->maxpaged('index', 10, $category_id);
        }
        $expiresAt = Carbon::now()->addMinutes(5);
        if (cache_has('rand_articles_index')) {
            $r->rand_articles = cache_get('rand_articles_index');
        } else {
            $r->rand_articles = Article::publish()->enl()->with('ez_map')->orderByRaw("RAND()")->take(10)->get();
            cache_put('rand_articles_index', $r->rand_articles, $expiresAt);
        }

        $r->plan = 1;
        return $r;
    }
    public function _getDataByPage()
    {

        $expert_articles = $this->get_expert_articles();

        $filter_ids = [];

        foreach ($expert_articles as $k => $a) {
            $filter_ids[] = $a->id;
        }

        return Article::publish()->other($filter_ids)->with('ez_map')->orderBy('publish_at', 'desc')->simplePaginate(4, ['*'], 'paged');
    }
    public function _show($id, $title = "")
    {

        /*
      $article = Article::leftJoin('articles_map', function ($join) {
           $join->on('articles.id', '=', 'articles_map.articles_id')->where('articles_map.site_id', '=', ff\config('site_id'));
       })
           ->where('articles_map.unique_id', '=', $id)
           ->with('author', 'ez_map', 'category','tagged')->first();
       */

        $expiresAt = Carbon::now()->addMinutes(5);
        /**讀取文章頁面 cache**/

        if (cache_has('article_' . $id)) {
            $article = cache_get('article_' . $id);
            $article_id = $article->id;
        } else {
            $article_id = $this->id_to_article_id($id);
            $article = Article::with('author', 'ez_map', 'category', 'tagged')->find($article_id);
            cache_put('article_' . $id, $article, $expiresAt);

        }

        //  dd($article->fb_publish_at);
        //->toIso8601String()
        //情色文章類別強制導到 …dark.eznewlife.com
        $comic_url=check_comic_show($article);
        $dark_url=check_dark_show($article);
        //  dd($dark_url);
        if (!empty($comic_url)) return redirect($comic_url);

        if (!empty($dark_url)) return redirect($dark_url);

        $data = Input::all();


        //分頁設計
        /*
        $article->content_pages=explode("enl",$article->page);
        $article->content_count=count(content_page);
        if(!isset($data['page']) or !epmpty($data['page'])) $data['page']=1;
        $content_page_key=$data['page']-1;
        $article->content_current=$article->content_page[$content_page_key];
        $article->content_page=$data['page'];
        dd($article);
        */

        //$article_id = $this->id_to_article_id($id);
        //$article = Article::with('author', 'ez_map', 'category', 'tagged')->find($article_id);
        if (!$article) abort(404, "文章不存在");

        $article->articles_id = $article_id;

        $right_title = hyphenize($article->title);

        $title = hyphenize($title);

        if ($right_title != $title) {
            //  if(fdebug()){
            // echo $title."\n";
            // echo $right_title."\n";
            // echo $this->_spell_link($id, $right_title)."\n";
            // exit;
            // }
            $this->log_url($this->_spell_link($id, $right_title));
            if (isset($data['autotest'])) $autotest = "?autotest"; else $autotest = '';
            return redirect($this->_spell_link($id, $right_title) . $autotest);
            //return redirect(route('articles.show', ['id' => $id, 'title' => $right_title]));
        }

        if (ff\c('device')->is_mobile() == 1) {
            return ff\mobile_article($id, $article->articles_id, $right_title, $this);
        }
        $collects=enl_user_info();
        $categories = Category::getList();

        // $share_url =route('articles.show', ['id' => $id, 'title' => $right_title]);
        $share_url = $this->_spell_link($id, $right_title);
        // $this->current_page_url($article->articles_id);

        $page = ['title' => $article->category->name, 'sub_title' => $article->title,
            'url' => '', 'share_url' => $share_url, 'photo' => $article->photo];
        // $prev = Article::publish()->where('category_id', $article->category_id)->where('publish_at', '>', $article->publish_at)->orderBy('publish_at', 'asc')->first();
        //$next = Article::publish()->where('category_id', $article->category_id)->where('publish_at', '<', $article->publish_at)->orderBy('publish_at', 'desc')->first();
        /**讀取文章頁面 cache**/

        $tmp_cache = 'page_article_' . $id;
        if (cache_has($tmp_cache)) {
            $id_page = cache_get($tmp_cache);
            foreach ($id_page as $key => $val) {
                $$key = $val;
            }

        } else {
            $prev_link = $this->previous_link($article->articles_id);
            $next_link = $this->next_link($article->articles_id);
            $prev_title = $this->previous_title($article->articles_id);
            $next_title = $this->next_title($article->articles_id);
            $id_page = ['prev_link' => $prev_link, 'next_link' => $next_link, 'prev_title' => $prev_title, 'next_title' => $next_title];
            cache_put($tmp_cache, $id_page, $expiresAt);

        }


        if (cache_has('rand_articles_show_' . $id)) {
            $rand_articles = cache_get('rand_articles_show_' . $id);
        } else {
            $rand_articles = Article::publish()->enl()->with('ez_map')->orderByRaw("RAND()")->take(10)->get();
            cache_put('rand_articles_show_' . $id, $rand_articles, $expiresAt);
        }

        // $rand_articles = Article::publish()->with('ez_map')->orderByRaw("RAND()")->take(10)->get();
        //var_dump($rand_articles);exit;
        $plan = 1;
        if ($article->flag === 'P') $plan = 2;

        /*
        return view('articles.show')->withArticle($article)
            ->with('rand_articles', $rand_articles)
            ->withPage($page)
            //->withPrev($prev)->withNext($next)
            ->with('prev_link', $prev_link)
            ->with('next_link', $next_link)
            ->with('prev_title', $prev_title)
            ->with('next_title', $next_title)
            ->with('plan', $plan)
            ->with('categories', $categories);


        */
        //temp

        $prev = Article::publish()->enl()->with('ez_map')->where('category_id', $article->category_id)->where('publish_at', '<', $article->publish_at)->orderBy('publish_at', 'desc')->first();
        if ($prev) {
            $test_link = route('articles.show', $prev->ez_map[0]->unique_id);
        } else $test_link = '';


        //分頁處理
        $article = content_paging($article);
        // <img src="../../../uploads/1454403220-eMToV.jpg" alt="" width="600">
        //  <img  data-original "../../../uploads/1454403220-eMToV.jpg" alt="" width="600" />
        $content = view('articles.show')->withArticle($article)
            ->with('rand_articles', $rand_articles)
            ->withPage($page)
            //->withPrev($prev)->withNext($next)
            ->with('test_link', $test_link)
            ->with('prev_link', $prev_link)
            ->with('next_link', $next_link)
            ->with('prev_title', $prev_title)
            ->with('next_title', $next_title)
            ->with('plan', $plan)
            ->with('collects', $collects)

            ->with('categories', $categories);

        //if ( Auth::enl_user()->check()===false )

        $a = \fcache::make()->save($content)->costTime();
        var_dump($a);
        return $content;


    }
    public function maxpaged($type, $n = 10, $category_id = null)
    {
        $conn = ff\conn();

        if ($type === 'index') {
            $sql = "select count(id) as c from articles where status='1'";
            $row = $conn->getOne($sql);
            $c = $row['c'];
            $maxpaged = ceil($c / $n);
            return $maxpaged;
        }

        if ($type === 'category') {
            $sql = "select count(id) as c from articles where status='1' and category_id=:category_id";
            $row = $conn->getOne($sql, $category_id);
            $c = $row['c'];
            $maxpaged = ceil($c / $n);
            return $maxpaged;
        }
    }
    protected function get_expert_articles()
    {
        $expiresAt = Carbon::now()->addMinutes(5);
        if (cache_has('expert_articles')) {
            $expert_articles = cache_get('expert_articles');
        } else {
            $expert_articles = Article::publish()->expert()->with('ez_map')->orderBy('publish_at', 'desc')->take(5)->get();
            cache_put('expert_articles', $expert_articles, $expiresAt);
        }
        return $expert_articles;

    }
}

<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2015/12/22
 * Time: 上午 10:50
 */
function https($url){
    $url=str_replace("http:","https:",$url);
    return $url;
}
function see_also($url){
    if (preg_match("/http:\/\//i",$url)){
        return urldecode(str_replace("http","https",$url));
    }
    if (preg_match("/https:\/\//i",$url)){
        return urldecode(str_replace("https","http",$url));
    }
}
function Gradient($HexFrom, $HexTo, $ColorSteps)
{
    $FromRGB['r'] = hexdec(substr($HexFrom, 0, 2));
    $FromRGB['g'] = hexdec(substr($HexFrom, 2, 2));
    $FromRGB['b'] = hexdec(substr($HexFrom, 4, 2));

    $ToRGB['r'] = hexdec(substr($HexTo, 0, 2));
    $ToRGB['g'] = hexdec(substr($HexTo, 2, 2));
    $ToRGB['b'] = hexdec(substr($HexTo, 4, 2));

    $StepRGB['r'] = ($FromRGB['r'] - $ToRGB['r']) / ($ColorSteps - 1);
    $StepRGB['g'] = ($FromRGB['g'] - $ToRGB['g']) / ($ColorSteps - 1);
    $StepRGB['b'] = ($FromRGB['b'] - $ToRGB['b']) / ($ColorSteps - 1);

    $GradientColors = array();

    for ($i = 0; $i <= $ColorSteps; $i++) {
        $RGB['r'] = floor($FromRGB['r'] - ($StepRGB['r'] * $i));
        $RGB['g'] = floor($FromRGB['g'] - ($StepRGB['g'] * $i));
        $RGB['b'] = floor($FromRGB['b'] - ($StepRGB['b'] * $i));

        $HexRGB['r'] = sprintf('%02x', ($RGB['r']));
        $HexRGB['g'] = sprintf('%02x', ($RGB['g']));
        $HexRGB['b'] = sprintf('%02x', ($RGB['b']));

        $GradientColors[] = implode(NULL, $HexRGB);
    }
    $GradientColors = array_filter($GradientColors, "len");
    return $GradientColors;
}

function len($val)
{
    return (strlen($val) == 6 ? true : false);
}

function random_color_part()
{
    return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
}

function random_color()
{
    return random_color_part() . random_color_part() . random_color_part();
}

function random_num_color()
{
    return rand(0, 255) . "," . rand(0, 255) . "," . rand(0, 255);
}

/**
 * SEO 麵包屑
**/
function seo_breadcrumb_list($site, $category, $categroy_url = '')
{

    return '<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="hidden">
            <li itemprop="itemListElement" itemscope
                itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="' . url("/") . '">
                    <span itemprop="name">' . $site . '</span></a>
                <meta itemprop="position" content="1" />
            </li>
            <li itemprop="itemListElement" itemscope
                itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="' . $categroy_url . '">
                    <span itemprop="name">' . $category . '</span></a>
                <meta itemprop="position" content="2" />
            </li>
        </ol>';
}

/**
 * SEO tag
 **/
function seo_meta_tag($article)
{
    $meta_tags = '';
    if ($article->tags->pluck('name')->count() > 0) {
        foreach ($article->tags->pluck('name')->all() as $key => $tag_name) {
            $meta_tags .= '<meta property="article:tag" content="' . $tag_name . '"/>';
        }
    }
    return $meta_tags;
}

function page_show($route, $count = null, $page = null)
{
    // echo "$route,$count,$page";
    $page_show = '';
    if (Route::currentRouteName() == $route and $count == 1) {
        $page_show = "";
    } elseif (Route::currentRouteName() == $route and $count > 1) {
        $page_show = "- 第" . $page . "頁";

    } else {
        $curr_route = explode(".", Route::currentRouteName());
        if (in_array($curr_route[0], ["avbodies", "getezs", "darks"])) {
            $page_show = "- 第" . Input::get('page', 1) . "頁";;
        } else if ($curr_route[0] == "articles") {
            $page_show = "- 第" . Input::get('paged', 1) . "頁";;
        }
    }
    if ($page == 1) $page_show = '';
    return $page_show;
}

function ranger($url)
{
    $headers = array(
        "Range: bytes=0-32768"
    );

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//	if ($_GET['miu']==1)dd($http_status);
    $data = curl_exec($curl);
    curl_close($curl);
//   var_dump($http_status);
    return $data;
}

function chmod_r($path)
{
    $dir = new DirectoryIterator($path);
    foreach ($dir as $item) {
        chmod($item->getPathname(), 0777);
        if ($item->isDir() && !$item->isDot()) {
            chmod_r($item->getPathname());
        }
    }
}

function save_para_cache()
{

    $tmp_cache = 'enl_categories';
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    $tmp_cate = App\Category::getList();
    cache_forever($tmp_cache, $tmp_cate);

    $tmp_cache = 'enl_categories_desc';
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    $tmp_cate = App\Category::enlDesc();
    cache_forever($tmp_cache, $tmp_cate);
    /**getez**/
    $tmp_cache = 'getez_categories';
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    $tmp_cate = App\Category::getez();
    cache_forever($tmp_cache, $tmp_cate);
    $tmp_cache = 'getez_categories_desc';
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    $tmp_cate = App\Category::getezDesc();
    cache_forever($tmp_cache, $tmp_cate);

    /*dark*/
    $tmp_cache = 'dark_categories';
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    $tmp_cate = App\Category::dark();
    cache_forever($tmp_cache, $tmp_cate);
    $tmp_cache = 'dark_categories_desc';
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    $tmp_cate = App\Category::darkDesc();
    cache_forever($tmp_cache, $tmp_cate);

    $tmp_cache = 'avbody_categories';
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    $tmp_cate = App\Category::avbody();
    cache_forever($tmp_cache, $tmp_cate);


    $tmp_cache = 'avbody_categories_desc';
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    $tmp_cate = App\Category::avbodyDesc();
    cache_forever($tmp_cache, $tmp_cate);

    $tmp_cache = 'god_categories';
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    $tmp_cate = App\Category::god();
    cache_forever($tmp_cache, $tmp_cate);

    //var_dump($tmp_cate);
    $tmp_cache = 'god_categories_desc';
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    $tmp_cate = App\Category::godDesc();
    cache_forever($tmp_cache, $tmp_cate);
    //var_dump($tmp_cate);
    $tmp_cache = 'enl_parameters';
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    $tmp_cate = App\Parameter::enl()->lists('data', 'name');
    cache_forever($tmp_cache, $tmp_cate);

    $tmp_cache = 'getez_parameters';
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    $tmp_cate = App\Parameter::getez()->lists('data', 'name');
    cache_forever($tmp_cache, $tmp_cate);


    $tmp_cache = 'dark_parameters';
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    $tmp_cate = App\Parameter::dark()->lists('data', 'name');
    cache_forever($tmp_cache, $tmp_cate);

    $tmp_cache = 'avbody_parameters';
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    $tmp_cate = App\Parameter::avbody()->lists('data', 'name');
    cache_forever($tmp_cache, $tmp_cate);

    $tmp_cache = 'god_parameters';
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    $tmp_cate = App\Parameter::god()->lists('data', 'name');
    //var_dump($tmp_cate);
    cache_forever($tmp_cache, $tmp_cate);
}

function save_tag_cache($name = null, $type = null)
{



//   $all_tags = App\Article::existingTags()->pluck('name')->all();

    $conn = oconn('M');

    $all_tags = [];

    $sql="select distinct tag_name from tagging_tagged;";
    $stmt = $conn->select($sql);
    while($row = $stmt->fetch(\PDO::FETCH_NUM)){
        //echo $row[0]."\n";
        $all_tags[]= $row[0];
    }





    $tmp_cache = 'tag_all';
    cache_forever($tmp_cache, $all_tags);
    if ($name != null) {
        unset($all_tags);
        $all_tags[] = $name;
    }

    


    //echo $type."<hr>";
    if ($type == "enl" or $name != null) {

        foreach ($all_tags as $k => $tname) {

            $tmp_cache = 'enl_tag_ids_' . ucfirst($tname);

            $tag_articles =\App\Article::publish()->enl()->with('tagged')->withAnyTag([$tname])->orderBy('publish_at')->lists('id')->toArray();
            
            
            if (count($tag_articles) > 0) cache_forever($tmp_cache, $tag_articles);
        }
    }



    if ($type == "dark" or $name != null) {
        foreach ($all_tags as $k => $tname) {
            $tmp_cache = 'dark_tag_ids_' . ucfirst($tname);
            $tag_articles =\App\Article::publish()->dark()->with('tagged')->withAnyTag([$tname])->orderBy('publish_at')->lists('id')->toArray();
            if (count($tag_articles) > 0) cache_forever($tmp_cache, $tag_articles);
            //echo $tmp_cache."<br>";
        }
    }

    /*
    if ($type == "god" or $name != null) {
        foreach ($all_tags as $k => $tname) {
            $tmp_cache = 'god_tag_ids_' . ucfirst($tname);
            $tag_articles = App\Article::publish()->god()->with('tagged')->withAnyTag([$tname])->orderBy('publish_at')->lists('id')->toArray();
            if (count($tag_articles) > 0) cache_forever($tmp_cache, $tag_articles);
            //echo $tmp_cache."<br>";
        }
    }
    */
    
}
function get_site_id($category_id){

    $parameters['enl'] = (cache_get('enl_parameters')->toArray());
    $parameters['dark'] = (cache_get('dark_parameters')->toArray());
    $parameters['getez'] = (cache_get('getez_parameters')->toArray());
    $parameters['avbody'] = (cache_get('avbody_parameters')->toArray());
    $parameters['god'] = (cache_get('god_parameters')->toArray());
 //  dd($parameters);
    foreach ($parameters as $k=>$p){
        $tmp=explode(",",$p["categories"]);
        if (in_array($category_id,$tmp)) return $p['site_id'];
    }

    //if (categorie_id)
}
function save_cate_cache($category_id)
{

    $tmp_loop = ['id' => 'ids', 'unique_id' => 'unique_ids'];
    $enl_categories = array_keys(cache_get('enl_categories')->toArray());
    $dark_categories = array_keys(cache_get('dark_categories')->toArray());
    $getez_categories = array_keys(cache_get('getez_categories')->toArray());
    $avbody_categories = array_keys(cache_get('avbody_categories')->toArray());
    $god_categories = array_keys(cache_get('god_categories')->toArray());



   // var_dump($god_parameters);
    $enl_all_loop = ['id' => 'enl_ids', 'unique_id' => 'enl_unique_ids', 'my_cate' => $enl_categories];
    $dark_all_loop = ['id' => 'dark_ids', 'unique_id' => 'dark_unique_ids', 'my_cate' => $dark_categories];
    $avbody_all_loop = ['id' => 'avbody_ids', 'unique_id' => 'avbody_unique_ids', 'my_cate' => $avbody_categories];
    $getez_all_loop = ['id' => 'getez_ids', 'unique_id' => 'getez_unique_ids', 'my_cate' => $getez_categories];
    $god_all_loop = ['id' => 'god_ids', 'unique_id' => 'god_unique_ids', 'my_cate' => $god_categories];
    $expert_all_loop = ['id' => 'expert_ids', 'unique_id' => 'expert_unique_ids', 'my_cate' => [1, 2]];
    $all = [$enl_all_loop, $dark_all_loop, $getez_all_loop, $avbody_all_loop, $expert_all_loop,$god_all_loop];
    foreach ($tmp_loop as $k => $v) {
        if (cache_has($v)) {
            $$v = cache_get($v);
        }
    }
    $site_id=get_site_id($category_id); if ($site_id !=6) $site_id=1;
    //echo $site_id;

    $cate_articles = App\Article::join('articles_map', function($q) use ($site_id){
        $q->on('articles_map.articles_id', '=', 'articles.id')
            ->where('articles_map.site_id','=',$site_id);
    })
        ->publish()->where('category_id', $category_id)
        ->orderBy('publish_at')->get(['articles_map.unique_id', 'articles.id'])->toArray();

    $tmp = [];
    foreach ($tmp_loop as $k => $v) {
        //var_dump( [$k,$v]);
        if (isset($$v)) {
            $tmp = $$v;
        }

        $tmp[$category_id] = array_column($cate_articles, $k);

        cache_forever($v, $tmp);
    };
    if (!in_array($category_id, $enl_categories)) unset($all[0]);
    if (!in_array($category_id, $dark_categories)) unset($all[1]);
    if (!in_array($category_id, $avbody_categories)) unset($all[3]);
    if (!in_array($category_id, $getez_categories)) unset($all[2]);
    if (!in_array($category_id, [1, 2])) unset($all[4]);
    if (!in_array($category_id, $god_categories)) unset($all[5]);
    foreach ($all as $i => $tmp_all_loop) {
        //var_dump($tmp_all_loop); echo"<br>";
        $all_articles = App\Article::join('articles_map', function($q) use ($site_id){
            $q->on('articles_map.articles_id', '=', 'articles.id')
                ->where('articles_map.site_id','=',$site_id);
        })
            ->publish()
            ->whereIn('category_id', $tmp_all_loop['my_cate'])
            ->orderBy('publish_at')
            //->lists('articles_map.unique_id','articles.id');
            ->get(['articles_map.unique_id', 'articles.id'])->toArray();
        // dd($cate_articles);
        foreach ($tmp_all_loop as $k => $v) {

            if ($k == 'my_cate') {
                //do nothing
            } else {
                $tmp = array_column($all_articles, $k);
                cache_forever($v, $tmp);
            }

        }
    }
    //dd([$v,$tmp]);

}

function save_article_cache($id)
{
    /**
     * save cache
     */

    $my_article = App\Article::with('author', 'ez_map', 'category', 'tagged')->find($id);
    if (!$my_article) abort(404, "文章已刪除");
    //dd($my_article);

    $tmp_cache = 'article_map_' . $my_article->ez_map[0]->unique_id;
    $tmp_cache_id = 'article_' . $id;
    //dd($tmp_cache);
    if (cache_has($tmp_cache)) {
        cache_forget($tmp_cache);
    }
    if (cache_has($tmp_cache_id)) {
        cache_forget($tmp_cache_id);
    }

    $my_article->content = article_handle_content($my_article->content);
    $my_article = content_paging($my_article);

    /*type*/
    $comic_url = check_comic_show($my_article);
    $dark_url = check_dark_show($my_article);
    $god_url = check_god_show($my_article);
    $my_article->type = 'enl';
    if (!empty($comic_url)) {
        $my_article->type = 'avbody';
        $my_article->rediret_url = $comic_url;
    } elseif (!empty($god_url)) {
        $my_article->type = 'god';
        $my_article->rediret_url = $god_url;
    } elseif (!empty($dark_url)) {
        $my_article->type = 'dark';
        $my_article->rediret_url = $dark_url;
    } else {
        $my_article->type = 'enl';
        $my_article->rediret_url = '';
    }
    if ($my_article->flag === 'P') {
        $my_article->plan = 2;
    } else {
        $my_article->plan = 1;
    }

    cache_forever($tmp_cache, $my_article);
    cache_forever($tmp_cache_id, $my_article);

    return $my_article;
}

function get_limit($total_count, $current_page, $limit)
{
    //$limit=get_limit(count($tag_ids),Input::get('page',1),10);
    if ($current_page == ceil($total_count / $limit)) {
        $limit = $total_count - ($current_page - 1) * $limit;
    } else {
        $limit;
    }
    return $limit;
}

function av_user_info($sort = null)
{
//dd($id);

    if (Auth::av_user()->check() === false) return [];

    // $tmp_cache='user_collect_' . Auth::av_user()->get()->id;
    //if (cache_has($tmp_cache)) {
    //   $articles = cache_get($tmp_cache);
    // } else {
    /*** 讀取用 slave*/
    $av_user_id = Auth::av_user()->get()->id;
    $collect = App\AvUserCollect::on('slave')->where('av_user_id', $av_user_id);
    $reorder = false;
    switch ($sort) {
        case "created_at.desc":

            $collect = $collect->orderBy("id", "desc");
            break;
        case "created_at.asc":
            $collect = $collect->orderBy("id", "asc");
            break;
        case "title.asc":
            $reorder = true;
            $sort_name = "title";
            $sort_order = "SORT_ASC";
            // $collect=$collect->orderBy("title","asc");
            break;
        case "title.desc":

            $reorder = true;
            $sort_name = "title";
            $sort_order = "SORT_DESC";
            // $collect=$collect->orderBy("title","desc");
            break;
        case "score.desc":
            $reorder = true;
            $sort_name = "score";
            $sort_order = "SORT_DESC";
            //  $collect=$collect->orderBy("score","desc");
            break;
        case "score.asc":
            $reorder = true;
            $sort_name = "score";
            $sort_order = "SORT_ASC";
            //   $collect=$collect->orderBy("score","asc");
            break;
        default:
            $collect = $collect->orderBy("id", "asc");
            break;
    }
    $collect = $collect->lists('created_at', 'article_id');

    //  Storage::prepend("aa.txt",date("Y-m-d H:i:s")."  ".$sort." ". Request::url()." ");
    //   $collect=Auth::av_user()->get()->collects()->lists('article_id');
    // echo $collect;
    if ($collect->count() == 0) return [];
    //if ($collect->count()==0)return [];
    $articles = new \stdClass();

    $i = 0;
    foreach ($collect as $k => $date) {
        if (cache_has('article_' . $k)) {
            $articles->articles[$k] = cache_get('article_' . $k);
            $articles->articles[$k]["collected_date"] = $date;
            $i++;
        }
    }

    if ($reorder == true) {
        Storage::prepend("aa.txt", date("Y-m-d H:i:s") . "  " . $sort . " " . Request::url() . " ");
        $articles->articles = array_orderby($articles->articles, $sort_name, constant($sort_order));

    }

    //if ($reorder==true) {
    // $articles->articles = searchArray('title', 'a', $articles->articles);
    //}
    //  dd($articles);
    // echo $collect;
    //  $articles=App\Article::with('ez_map')->publish()->whereIn('id',array_keys($collect->toArray()))->get();
    $articles->user_collects = $collect;
    $articles->collect_ids = $collect->toArray();
    //  cache_forever($tmp_cache, $articles);
    // }
    //var_dump($collects->count());

    return $articles;

}

/** 取得文章 cache 唯一 id*/
function get_unique_id($id)
{
    $article = cache_get('article_' . $id);
    return $article->ez_map[0]->unique_id;


}

function searchArray($key, $st, $array)
{
    foreach ($array as $k => $v) {
        if (strpos($v[$key], $st) === FALSE) {

        } else {
            $new = $v;
        }
    }
    return $new;
}

function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
        }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

function enl_user_info()
{
//dd($id);

    if (Auth::enl_user()->check() === false) return [];

    //$tmp_cache='enl_user_collect_' . Auth::enl_user()->get()->id;
    //if (cache_has($tmp_cache)) {
    //  $articles = cache_get($tmp_cache);
    // } else {
    //$collect=Auth::enl_user()->get()->collects()->lists('created_at','article_id');
    /*** 讀取用 slave*/

    $enl_user_id = Auth::enl_user()->get()->id;
    $collect = App\EnlUserCollect::on('slave')->where('enl_user_id', $enl_user_id)->lists('created_at', 'article_id');
    $articles = new \stdClass();

    $i = 0;
    foreach ($collect as $k => $date) {
        if (cache_has('article_' . $k)) {
            $articles->articles[$k] = cache_get('article_' . $k);
            $articles->created_at[$k] = $date;
            $articles->articles[$k]["collected_date"] = $date;

            $i++;
        }
    }

    // echo $collect;
    //  $articles=App\Article::with('ez_map')->publish()->whereIn('id',array_keys($collect->toArray()))->get();
    $articles->user_collects = $collect;
    //  cache_forever($tmp_cache, $articles);
    // }

    return $articles;
}

function set_enl_user_info()
{
//dd($id);

    if (Auth::enl_user()->check() === false) return [];

    $tmp_cache = 'enl_user_collect_' . Auth::enl_user()->get()->id;

    $collect = Auth::enl_user()->get()->collects()->lists('created_at', 'article_id');

    // echo $collect;
    $articles = App\Article::with('ez_map')->publish()->whereIn('id', array_keys($collect->toArray()))->get();
    $articles->user_collects = $collect;
    // dd($articles);
    cache_forever($tmp_cache, $articles);

    return $articles;
}

function cdn($asset)
{

    return asset($asset,secure());
    if (substr($asset, 0, 2) === '//') return $asset;
    if (strpos($asset, 'http') !== false) return $asset;

    $scheme = 'http:';
    if (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https') {
        $scheme = 'https:';
    }

    //return $scheme . "//" . Config::get('app.cdn') . "/" . ltrim($asset, "/");
    return $scheme . "//" . $_SERVER['SERVER_NAME'] . "/" . ltrim($asset, "/");
}

function cdn_KILL($asset)
{

    // Verify if KeyCDN URLs are present in the config file
    if (!Config::get('app.cdn'))
        return asset($asset);

    // Get file name incl extension and CDN URLs
    $cdns = Config::get('app.cdn');
    $assetName = basename($asset);

    // Remove query string
    $assetName = explode("?", $assetName);
    $assetName = $assetName[0];

    // Select the CDN URL based on the extension
    foreach ($cdns as $cdn => $types) {
        if (preg_match('/^.*\.(' . $types . ')$/i', $assetName))
            return cdnPath($cdn, $asset);
    }

    // In case of no match use the last in the array
    end($cdns);
    return cdnPath(key($cdns), $asset);

}

function cdnPath_KILL($cdn, $asset)
{
    return "//" . rtrim($cdn, "/") . "/" . ltrim($asset, "/");
}



function secure()
{
    //Request::server('REQUEST_SCHEME') ;
    if (Request::server('HTTP_X_FORWARDED_PROTO') == 'https' or Request::server('REQUEST_SCHEME')=="https") {
        return true;
    } else {
        return false;
    }
}



function secure2()
{

    if( isset($_SERVER['HTTP_X_FORWARDED_PROTO'])  && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'){
        return true;
    }


    if( isset($_SERVER['REQUEST_SCHEME'])  && $_SERVER['REQUEST_SCHEME'] === 'https'){
        return true;
    }


    return false;

}


function show_page_bar_scroll($article, $class)
{
    $bar = '';
    /*
$results->lastPage()
$results->currentPage()
$results->hasMorePages()
$results->lastPage() (Not available when using simplePaginate)
$results->nextPageUrl()
$results->perPage()
$results->previousPageUrl()
$results->total() (Not available when using simplePaginate)
$results->url($page)

    */
  // var_dump($article);
    //   dd($article->url(1));
    if ($article->lastPage() > 1) {
//dd( $article->currentPage());
        $bar .= '<form class="center-block" style="width:280px"> <div class="input-group ">';
        //  $bar.='<div class="input-group-btn"><a hrfe="" class="btn btn-default"><span class="glyphicon glyphicon-bold"></span></a>';
        $bar .= '<span class="input-group-btn"><a class="btn ' . $class . ' ';
        if ($article->currentPage() == 1) $bar .= ' disabled ';
        $bar .= '" href="'
            . $article->previousPageUrl()
            . '">上一頁</a>'
            . ' </span>';

        $bar .= '<select class="selectpicker form-control input-lg" data-live-search="true"  onchange="location = this.value;">';
        for ($i = 1; $i <= $article->lastPage(); $i++) {
            $bar .= ' <option ';
            if ($article->currentPage() == $i) $bar .= ' selected ';
            $bar .= 'value="' . $article->url($i) . '" >' . $i . '</option>' . "\n";
        }
        $bar .= '</select>';
        $bar .= '<span class="input-group-btn"><a class="btn ' . $class . ' ';
        if ($article->currentPage() == $article->lastPage()) $bar .= ' disabled ';
        $bar .= '" href="'
            . $article->nextPageUrl()
            . '"  ' . 'alt="' . $article->title . ' - 第' . $article->nextPageUrl() . '頁"  rel="prev">下一頁</a>'
            . ' </span>';
        $bar .= '  </div></form>';
        /*
        $bar .= '<li class="next  ';
        if ($article->next_page == null) $bar .= 'disabled';
        $bar .= '" > <a href = "';
        if ($article->next_page == null) $bar .= '#'; else $bar .= '?page=' . $article->next_page;
        $bar .= '" aria-label = "Next" ><span aria-hidden = "true" >»</span> </a > </li>'."\n";
        $bar .= '</ul></nav>';*/


    }

    return $bar;

}

function article_amp_content($content)
{
    $doc = new DOMDocument();
    @$doc->loadHTML($content);

    $tags = $doc->getElementsByTagName('img');
    $page = Input::get('page') ? Input::get('page') : 1;
    $img = [];
    foreach ($tags as $i => $tag) {
        $tmp_img = $tag->getAttribute('src');
        $width = $tag->getAttribute('width');
        $height = $tag->getAttribute('height');


        if (false === strpos($tmp_img, '://')) {
            $tmp_img = cdn($tmp_img);
        }
        /*
        $tmp = '<div itemprop="image" itemscope itemtype="http://schema.org/ImageObject">'
            . '<img itemprop="url" src="' . $tmp_img . '" class="img-responsive" alt="' . $title . "-" . $i . '">';
        if (!empty($width) and !empty($height)) {
            $tmp .= "  <meta itemprop=\"width\" content=\"$width\"/> <meta itemprop=\"height\" content=\"$height\"/>";
        }
        $tmp .= '</div>';*/
        $img[] = ['src' => $tmp_img, 'width' => $width, 'height' => $height];


    }
    //$content=implode('@enl@',$img);

    return $img;
}

function article_comic_content($content, $title = '')
{
    $doc = new DOMDocument();
    @$doc->loadHTML($content);

    $tags = $doc->getElementsByTagName('img');
    $page = Input::get('page') ? Input::get('page') : 1;

    foreach ($tags as $i => $tag) {
        $tmp_img = $tag->getAttribute('src');
        $width = $tag->getAttribute('width');
        $height = $tag->getAttribute('height');
        /*
        if (false === strpos($tmp_img, '://')) {
            $tmp_img = cdn($tmp_img);
        }*/
        //echo $tag->getAttribute('src');
        $tmp_img = str_replace("cdn.eznewlife.com", 'avbody.info', $tmp_img);
        //$img[]='<a href="'.$tag->getAttribute('src').'"><img src="'.$tag->getAttribute('src').'" class="img-responsive" data-elem="pinchzoomer"></a>';
        $tmp_size = '';


        $tmp = '<div itemprop="image" itemscope itemtype="http://schema.org/ImageObject">'
            . '<img itemprop="url" src="' . $tmp_img . '" class="img-responsive" alt="' . $title . "-" . $i . ' "'
            . 'itemprop="contentUrl" ' . '>';
        if (!empty($width) and !empty($height)) {
            $tmp .= "  <meta itemprop=\"width\" content=\"$width\"/> <meta itemprop=\"height\" content=\"$height\"/>";
        }
        $tmp .= '</div>';
        $img[] = $tmp;


    }
    $content = implode('@enl@', $img);

    return $content;
}


function avuser_article_comic_content($content, $ad_code, $title = '')
{
    $doc = new DOMDocument();
    @$doc->loadHTML($content);

    $tags = $doc->getElementsByTagName('img');
    $p = $ins_ad = 0;
    $img = [];
    $page = Input::get('page') ? Input::get('page') : 1;
    foreach ($tags as $i => $tag) {
        //echo $tag->getAttribute('src');//{!! get_adCode(1, $plan, __DOMAIN__) !!}
        //$img[]='<a href="'.$tag->getAttribute('src').'"><img src="'.$tag->getAttribute('src').'" class="img-responsive" data-elem="pinchzoomer"></a>';
        $page_ad = '<div class="text-center adv mar-tb-10" id="ad_block_' . $ad_code . '_' . $i . '">	' . get_adCode($ad_code, 2, __DOMAIN__) . '</div>';
        $ins_ad++;
        if ($i % 5 == 0) {
            $img[$p] = '';
        }
        $tmp_img = $tag->getAttribute('src');
        $width = $tag->getAttribute('width');
        $height = $tag->getAttribute('height');
        $tmp_img = str_replace("cdn.eznewlife.com", 'avbody.info', $tmp_img);
        $tmp_size = '';

        $img[$p] .= '<div itemprop="image"  itemscope itemtype="http://schema.org/ImageObject">';
        $img[$p] .= '<img  itemprop="url" src="' . $tmp_img . '" class="img-responsive img-thumbnail comic-border" alt="' . $title . "-" . $i . '" >';
        if (!empty($width) and !empty($height)) {
            $img[$p] .= "  <meta itemprop=\"width\" content=\"$width\"/> <meta itemprop=\"height\" content=\"$height\"/>";
        }

        $img[$p] .= '</div>';

        if ($ins_ad == 2) $img[$p] .= $page_ad;
        if ($ins_ad == 4) $img[$p] .= $page_ad;
        if ($i % 5 == 4) {

            $p++;
            $ins_ad = 0;
        }
    }
    $content = implode('@enl@', $img);
    return $content;
}

function add_micro($o)
{
    //micro 加 width height
    $pattern = '/src=("[^"]*")(.*width)="([^"]*)"([^>]*)/i';
    $replace = 'src=$1$2="$3"$4>' . '<meta itemprop="width" content="$3"/';
    $o = preg_replace($pattern, $replace, $o);

    $pattern = '/src=("[^"]*")(.*height)="([^"]*)"[^>]*/i';
    $replace = 'src=$1$2="$3"$4>' . '<meta itemprop="height" content="$3"/';
    $o = preg_replace($pattern, $replace, $o);
    return $o;
}

function multiexplode($delimiters, $string)
{

    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return $launch;
}


function article_instant_content($content)
{
    //  $my_ad='<figure class="op-ad"><iframe width="300" height="250" style="border:0; margin:0;" src="https://www.facebook.com/adnw_request?placement=643012882521252_643012889187918&adtype=banner300x250"></iframe></figure>';
    //將所有br 改成<p>
    //去雜質

    $content = str_replace('admin.eznewlife.com', 'eznewlife.com', $content);

    $content = str_replace('../../../', 'http://eznewlife.com/', $content);
    $content = str_replace('../../', 'http://eznewlife.com/', $content);
    $content = str_replace('src="/focus_photos/', 'src="http://eznewlife.com/focus_photos/', $content);
    $content = str_replace('src="/uploads/', 'src="http://eznewlife.com/uploads/', $content);
    $content = str_replace('src="/comic/images', 'src="http://eznewlife.com/comic/images', $content);

    $content = str_replace("\xc2\xa0", '', $content);
    //去除其它字元
    $content = trim($content, " \t\n\r\0\x0B\xC2\xA0");
    $content = preg_replace('/[<]br[^>]*[>]/i', '<p>', $content);
    // $content = remove_empty_p($content);

    //連結外面加p

    $content = preg_replace('/(<a[^>]*>.*?<\/a>)/i', '<p>$1</p>', $content);

    //style 紅色是強調
    $reg = '/<(\w+)>(.*?)<\/\1>/';
    $content = preg_replace('/<span([^<]*)style="([^<]*)color: #ff0000;([^<]*)">([^<]*)<\/span>/i', '</p><aside>$4</aside><p>', $content);

    //pp($content);
   // pp(strlen($content));
    $content=Purifier::clean($content);
   // pp($content);
  //  pp(strlen($content));
    /// h1 -h6 是強調
    //$content  = preg_replace('/<h[1-6]>(.*?)<\/h[1-6]>/', '<adide>$1<adide>',  $content );
    /***所有div 換成p*/
    $content = preg_replace('#(<div(.*?)></div>)#', ' <p>' . '$1' . '</p>', $content);
    $content = preg_replace('#(<span(.*?)></span>)#', ' <p>' . '$1' . '</p>', $content);
    // $content = preg_replace('/(width|height)="\d*"\s/', "", $content);
    $content = preg_replace('/(<[^>]+) (rel|alt)=".*?"/i', '$1', $content);
    $content = preg_replace('/(<[^>]+) class=".*?"/i', '$1', $content);
    $content = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $content);
    $content = preg_replace('/(<[^>]+) id=".*?"/i', '$1', $content);
    $content = preg_replace('/(<[^>]+) data-.*?=".*?"/i', '$1', $content);
    // $content = preg_replace('/(<span style="[^>]*>)(.*?)(<\/span>)/i', '<strong>$2</strong>', $content);

    $content = preg_replace('/(<strong[^>]*>)(.*?)(<\/strong>)/i', '<p><strong>$2</strong></p>', $content);
    $content = preg_replace('/<p>(<strong[^>]*>)(.*?)(<strong[^>]*>)(.*?)(<\/strong>)(<\/strong>)<\/p>/i', '<p><strong>$2$4</strong></p>', $content);
//pp($content);

    //  $content = preg_replace('/(<span[^>]*>)(.*?)(<\/span>)/i', '<p><strong>$2</strong></p>', $content);
    /**圖片去除 <a>*/
    $content = preg_replace('/<a href="[^"]*">[^<]*<img src="[^"]*"[^>]*>[^<]*<\/a>/sim', '', $content);
    /**去空圖*/
    $content = str_replace('<img>', '', $content);
    $content = str_replace('<img alt="" />', '', $content);
    $content = str_replace('@enl@', '', $content);

    //移除圖片的 link

//   $content= preg_replace('#(<a [^>]+)#', '<p>'.'$1'.'></p',$content);
    //echo $content;exit;
    //a 外面加個p
    // dd($content);
    //圖片加figure
    $content = strip_tags($content, '<img><p><a><strong><iframe><aside>');

    $content = pass_iframe($content);
    $content = only_one_image($content);

    $content = preg_replace('#(<img [^>]+)#', '<figure data-feedback="fb:likes, fb:comments">' . '$1' . '></figure', $content);

    $content = remove_empty_p($content);

    $content = remove_empty_tags_recursive($content);

    $content = unclosed_tag($content);

    //去掉 figure 外面有個p ,a
    $content = clear_p($content);
    $content = preg_replace('/(<p[^>]*>\s?)(.*?)(<figure.*?)(<\/p>)/i', '<p>$2</p>$3', $content);
    $content = remove_empty_tags_recursive($content);
    //pp($content);
    //去雜質
    $content = str_replace("\xc2\xa0", '', $content);
    //去除其它字元
    $content = trim($content, " \t\n\r\0\x0B\xc2\xa0");
    //去除空白字元
    $pattern = "/<p[^>]*>(\s|&nbsp;)*?<\/p[^>]*>/i"; // regular expressi
    $content = preg_replace('/(<p[^>]*>\s?)(<figure.*?)(<\/p>)/i', '$2', $content);
    $content = preg_replace($pattern, '', $content);

    // $content=preg_replace($reg,'aaa',$content);

    $reg = '/<(\w+)>(\s|&nbsp;)*<\/\1>/';
    //去空ｔａｇ
    $content = preg_replace($reg, '', $content);
    $content = unclosed_tag($content);
    $reg = '/<(\w+)>(\s|&nbsp;)*<\/\1>/';
    //去空ｔａｇ
    $content = preg_replace($reg, '', $content);
    // $content = un_expend_tags($content);

    $content = preg_replace('/(<p[^>]*>\s?)(\s)*(<figure.*?)(<\/p>)/i', '$2', $content);
    $content = check_p('/(<figure(.*?)><\/figure>)/i', $content);
    $content = check_p('/(<aside>(.*?)<\/aside>)/i', $content);

    $content = check_p('/(<p>(.*)<\/p>)/i', $content);
    $content = preg_replace("/\r\n|\r|\n/", '', $content);
    $content = preg_replace('/(<p[^>]*>\s?)(\s)*(<figure.*?)(<\/p>)/i', '<p>$2</p>$3', $content);
    $content = unclosed_tag($content);
    $content = preg_replace('/(<p[^>]*>)(\s?)(.*?)(\s?)(<\/p>)/i', '$1$3$5', $content);
    $content = preg_replace('#<p>(\s|&nbsp;)*+(<br\s*/*>)*(\s|&nbsp;)*</p>#i', '', $content);
    //
   // pp($content);
   $content = add_p($content);
    $content = remove_empty_p($content);
  ///  pp($content);
    $content = preg_replace("/<p[^>]*><strong[^>]*>(.*?)<\/strong><\/p>/i", "<aside>$1</aside>", $content);
    $content = preg_replace('/(<p[^>]*>\s?)(\s)*(<figure.*?)(<\/p>)/i', '<p>$2</p>$3', $content);
   // pp($content);
    return $content;

}

//沒包tag 包個 <P> tag刪除
function add_p($content)
{
    $content = preg_replace("/\r\n|\r|\n/", '', $content);
    $org_content = $content;
    $content = preg_replace("/<figure[^>]*>.*?<\/figure>/i", "", $content);
    $content = preg_replace("/<aside[^>]*>.*?<\/aside>/i", "", $content);
    $content = preg_replace("/<p[^>]*>.*?<\/p>/i", "<cut/>", $content);
//echo "<hr>";
   // pp($content);

    $cut = explode('<cut/>', $content);
    $cut = array_filter($cut, function ($value) {
        return trim($value) != '' and trim($value)!=":"  and trim($value)!="/" and trim($value)!="h" and trim($value)!="t" and trim($value)!="p";
    });
  //  var_dump($cut);

    if (count($cut) > 0) {
        foreach ($cut as $v) {
            if($v==".") continue;
            $org_content = str_replace($v, "<p>$v</p>", $org_content);;
        }
      //  echo "1<hr>";
       // pp($org_content); echo "2<hr>";
        $org_content = preg_replace("/(<figure.*?>.*?<\/figure>)/i", "$1\n", $org_content);//pp($org_content);

        $org_content = preg_replace("/(<p.*?>.*?<\/p>)/i", "$1\n", $org_content);//pp($org_content);
        $org_content = preg_replace("/(<aside.*?>.*?<\/aside>)/i", "$1\n", $org_content);//pp($org_content);
    }
  $org_content=str_replace("</p>\n<p>)</p>\n",")</p>\n" ,$org_content);
    $org_content=str_replace("<p></aside>\n</p>","</aside>\n" ,$org_content);
    $org_content=str_replace("<p></aside>\n</p>","</aside>\n" ,$org_content);
    $org_content=str_replace("<p></p>","" ,$org_content);
  //  pp($org_content);
    return $org_content;
    $content = $org_content;

}

function trim_p($content)
{
    $doc = new DOMDocument();
    @$doc->loadHTML($content);

    $tags = $doc->getElementsByTagName('p');

}

function clear_p($content)
{
    $content = preg_replace('/(<a[^>]*>\s?)(<figure.*?)(<\/a>)/i', '$2', $content);
    $content = preg_replace('/(<p[^>]*>\s?)(<figure.*?)(<\/p>)/i', '$2', $content);
    $content = preg_replace('/(<p[^>]*>\s?)(<aside.*?)(<\/p>)/i', '$2', $content);
    return $content;
}

function pass_iframe($content)
{
    $count = preg_match_all('/<iframe[^>]+>/i', $content, $iframes);
    $url = url("/") . "/";
    //echo $url."<br>";
    //$url="http://cdn.eznewlife.com/";
    if ($count === 0) return $content;
    foreach ($iframes[0] as $i => $iframe) {
        $regex = '~<[^>]*?src="([^"]+)"[^>]*>~';
        $tmp_iframe_url = preg_replace($regex, "$1", $iframe);
        //有設 寬高的iframe
        $is_width = preg_match_all('/ width="([^"](\d+))"/i', $iframe, $alt);
        $is_height = preg_match_all('/ height="([^"](\d+))"/i', $iframe, $alt);

        if ($is_width == 0 or $is_height == 0) {
            $content = preg_replace('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', '<figure class="op-interactive"> <iframe src="$1" ></iframe></figure>', $content);
        } else {
            $content = preg_replace('/<iframe(.*)src=\"(.*)\"(.*)width="(\d+)"(.*)height="(\d+)"(.*)><\/iframe>/i', '<figure class="op-interactive"> <iframe src="$2" width="$4" height="$6"></iframe></figure>', $content);
        }
    }
    return $content;
}

function add_article_attribute($id, $target = 'id', $do = 'save')
{

    if ($target == 'id') {
        $my_article = App\Article::with('author', 'ez_map', 'category', 'tagged')->find($id);
        $html_content = $my_article->content;
    } else {
        $html_content = $id;
    }
    $html_content = str_replace(' width="" height="" ', '', $html_content);
    $count = preg_match_all('/<img[^>]+>/i', $html_content, $images);
    $url = url("/") . "/";
    //echo $url."<br>";
    //$url="http://cdn.eznewlife.com/";
    if ($count === 0) return $html_content;
    $o = $html_content;
    //  dd($images[0]);
    foreach ($images[0] as $i => $img) {
        //echo $img."<br>";
        $regex = '~<[^>]*?src="([^"]+)"[^>]*>~';
        if (!preg_match("/ src=\"(https:\/\/|http:\/\/|\/\/)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $img)) {
            //  echo $i."aaa<br>";
            $tmp_img_url = str_replace("../../../", "", preg_replace($regex, "$1", $img));
            $tmp_img_url = str_replace("/uploads", "uploads", $tmp_img_url);
            $tmp_img_url = str_replace("/comic", "comic", $tmp_img_url);
            $tmp_img_url = $url . $tmp_img_url;
        } else {
            // echo $i."bbb<br>";
            $tmp_img_url = preg_replace($regex, "$1", $img);
            $http = explode("//", $tmp_img_url);
            if (empty($http[0])) $http[0] = 'http:'; // ex //eznewlife.com 要補上http:
            $tmp_img_url = implode("//", $http);
        }
        if (empty($tmp_img_url) or $tmp_img_url == $url) {
            contiune;
        }
        // echo $tmp_img_url."<br>";
        list($width, $height) = getimagesize($tmp_img_url);

        $t = "width=\"$width\" height=\"$height\" ";

        $is_width = preg_match_all('/ width="([^"]*)"/i', $img, $alt);
        $is_height = preg_match_all('/ height="([^"]*)"/i', $img, $alt);

        if ($is_width == 0 and $is_height == 0) {
            $new_img = preg_replace('/<img(.*?)src="(.*?)"(.*?)/i', '<img$1src="$2" $3' . $t, $img);
            $o = str_replace($img, $new_img, $o);
        }

    }

    //echo ("<textarea cols='80' rows='20'>".$o."</textarea>");
//die;
    if ($do == 'read') {
        //只有回傳結果
        //dd(['read',$o]);
        return $o;
    } else {
        //還要儲存

        echo("<textarea cols='80' rows='20'>" . $o . "</textarea>");
        echo '<a href="' . Request::url() . '?id=' . $id . '&ok">' . $id . ' ok</a>';
        if (isset($_GET['ok'])) {
            $my_article->content = $o;
            $my_article->save();
            save_article_cache($id);
            save_cate_cache($my_article->category_id);
        }
        //dd($o);
    } //echo $o;
}

function autop($content)
{
    // If br set, this will convert all remaining line-breaks after paragraphing.
    $br = true;
    $content = preg_replace('|<br />\s*<br />|', "\n\n", $content . "\n");
    // Space things out a little
    $allBlocks = '(?:table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|map|area|blockquote|address|math|style|input|p|h[1-6]|hr)';
    $content = preg_replace('!(<' . $allBlocks . '[^>]*>)!', "\n$1", $content);
    $content = preg_replace('!(</' . $allBlocks . '>)!', "$1\n\n", $content);
    // dd($content);
    $content = str_replace(array("\r\n", "\r"), "\n", $content); // cross-platform newlines
    if (strpos($content, '<object') !== false) {
        $content = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $content); // no content inside object/embed
        $content = preg_replace('|\s*</embed>\s*|', '</embed>', $content);
    }
    $content = preg_replace("/\n\n+/", "\n\n", $content); // take care of duplicates

    // make paragraphs, including one at the end
    $contents = preg_split('/\n\s*\n/', $content, -1, PREG_SPLIT_NO_EMPTY);
    $content = '';
    foreach ($contents as $tinkle) {
        $content .= '<p>' . trim($tinkle, "\n") . "</p>\n";
    }
    $content = preg_replace('|<p>\s*?</p>|', '', $content); // under certain strange conditions it could create a P of entirely whitespace

    $content = preg_replace('!<p>([^<]+)\s*?(</(?:div|address|form)[^>]*>)!', "<p>$1</p>$2", $content);
    $content = preg_replace('|<p>|', "$1<p>", $content);
    $content = preg_replace('!<p>\s*(</?' . $allBlocks . '[^>]*>)\s*</p>!', "$1", $content); // don't content all over a tag

    $content = preg_replace("|<p>(<li.+?)</p>|", "$1", $content); // problem with nested lists
    $content = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $content);
    $content = str_replace('</blockquote></p>', '</p></blockquote>', $content);
    $content = preg_replace('!<p>\s*(</?' . $allBlocks . '[^>]*>)!', "$1", $content);
    $content = preg_replace('!(</?' . $allBlocks . '[^>]*>)\s*</p>!', "$1", $content);
    if ($br) {
        $content = preg_replace_callback('/<(script|style).*?<\/\\1>/s', create_function('$matches', 'return str_replace("\n", "<WPPreserveNewline />", $matches[0]);'), $content);
        $content = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $content); // optionally make line breaks
        $content = str_replace('<WPPreserveNewline />', "\n", $content);
    }
    $content = preg_replace('!(</?' . $allBlocks . '[^>]*>)\s*<br />!', "$1", $content);
    $content = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $content);
    if (strpos($content, '<pre') !== false) {
        $content = preg_replace_callback('!(<pre.*?>)(.*?)</pre>!is', array('self', 'cleanPre'), $content);
    }
    $content = preg_replace("|\n</p>$|", '</p>', $content);
    // 	$content = preg_replace('/<p>\s*?(' . get_shortcode_regex() . ')\s*<\/p>/s', '$1', $content); // don't auto-p wrap shortcodes that stand alone


    return $content;
}


function article_instant_ad($content, $my_ad)
{
    $ad = explode("<figure", $content);
    if (count($ad) > 2) {
        $ad[1] = $ad[1] . $my_ad;
    }
    $content = implode("<figure", $ad);
    return $content;
}

function remove_empty_tags_recursive($str, $repto = NULL)
{
    //** Return if string not given or empty.
    if (!is_string($str)
        || trim($str) == ''
    )
        return $str;

    //** Recursive empty HTML tags.
    return preg_replace(
    //** Pattern written by Junaid Atari.
        '/<([^<\/>]*)>([\s]*?|(?R))<\/\1>/imsU',

        //** Replace with nothing if string empty.
        !is_string($repto) ? '' : $repto,

        //** Source string
        $str
    );
}

function remove_empty_p($content)
{
    // clean up p tags around block elements
    $content = preg_replace(array(
        '#<p>\s*<(div|aside|section|article|header|footer)#',
        '#</(div|aside|section|article|header|footer)>\s*</p>#',
        '#</(div|aside|section|article|header|footer)>\s*<br ?/?>#',
        '#<(div|aside|section|article|header|footer)(.*?)>\s*</p>#',
        '#<p>\s*</(div|aside|section|article|header|footer)#',
    ), array(
        '<$1',
        '</$1>',
        '</$1>',
        '<$1$2>',
        '</$1',
    ), $content);

    return preg_replace('#<p>(\s|&nbsp;)*+(<br\s*/*>)*(\s|&nbsp;)*</p>#i', '', $content);
}

function unclosed_tag($content)
{
    libxml_use_internal_errors(true);
    $doc = new DOMDocument('1.0', 'UTF-8');
    // $meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
    // @$dom->loadHTML($meta . $html);
    $doc->loadHTML('<?xml encoding="utf-8"?>' . $content);
    $doc->encoding = 'utf-8';

    $content = $doc->saveHTML($doc->documentElement) . PHP_EOL . PHP_EOL;

    $content = str_replace("<html><body>", '', $content);
    $content = str_replace("</body></html>", '', $content);
    //pass_p($content);
    return $content;
}

function check_p($ereg, $content)
{
    $strings = preg_split($ereg, $content);

    foreach ($strings as $i => $p) {
        //pp($p);
        $p = trim($p);
        if (empty($p)) continue;
        if (substr($p, 0, 1) != "<") {
            //第一個字 < 開頭
            $f = explode("<", $p);
            $content = str_replace($f[0], "<p>" . $f[0] . "</p>", $content);
            // pp($p);
        }
        $closed_p = unclosed_tag($p);
        $reg = '/<(\w+)>(\s|&nbsp;)*<\/\1>/';
        //去空ｔａｇ
        $closed_p = preg_replace($reg, '', $closed_p);
        if ($closed_p != $p) {
            $content = str_replace($p, $closed_p, $content);
        }

    }
    // exit;
    //dd($s);

    // dd($content);

    return $content;

}

function pp($content)
{
   // echo __FUNCTION__;echo "<br>";
  echo "<textarea cols='100' rows='5'>$content</textarea><br>";
}
function trim_img($content){
     $content=str_replace('<img alt="" />',"",$content);
    $content=str_replace('<img alt="">',"",$content);
    return $content;



    $doc = new DOMDocument();
    @$doc->loadHTML($content);

    $tags = $doc->getElementsByTagName('img');
    foreach ($tags as $tag) {
        if (empty($tag->getAttribute('src'))) {
        /**不區分大小寫，取代空圖*/
            $regex = '/<img[^>]?(src=)?'.addcslashes($tag->getAttribute('src'), "/") . '[^>]*>/i';
            $content = preg_replace($regex, '', $content);
         }

    }
    return $content;

}
function only_one_image($content)
{
    $doc = new DOMDocument();
    @$doc->loadHTML($content);

    $tags = $doc->getElementsByTagName('img');
    $img = array();
    //  pp($content);
    //echo $content."<br>";
    foreach ($tags as $tag) {


        if (strpos($tag->getAttribute('src'), 'gif')) {
            $size = getimagesize($tag->getAttribute('src'));
            //小的動圖要刪了
            //  dd($size);
            if ($size[0] <= 100) {
                //  echo $tag->getAttribute('src');
                //  $content= preg_replace('~<img .*?\..".*?/>~sim', '',  $content);
                //  $content = preg_replace('/(<[^>]+) src="$tag->getAttribute(\'src\')"/i', '$1', $content);
                '/<img(.*)src="http://cdn.eznewlife.com/uploads/1469414304-ERl4l.gif"[^>]+>/';
                //echo

                $regex = '/<img[^>]+src="' . addcslashes($tag->getAttribute('src'), "/") . '"[^>]*>/i';
                //  echo $regex."<br>";
                $content = preg_replace($regex, '', $content);
            }
            //  dd($content);
        }

        $img[] = $tag->getAttribute('src');

    }
//echo $content;exit;
    $img_count = array_count_values($img);

    if ($img_count > 0) {
        foreach ($img_count as $img_source => $count) {

            if ($count > 1) {
                $regex = '#(<img)(.*?)(' . $img_source . ')(.*?)(/>)#';
                $content = preg_replace($regex, '', $content, $count - 1);
            }
            //if (!preg_match("/(https:\/\/|http:\/\/|\/\/)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $img_source)) {
            //  $content = str_replace("//" . $tag . $img_source, "http://" . $img_source, $content);
            //}

        }
    }

    return $content;

}

//不要那 enl 的文章 跟 comic 有關連
function check_comic_show($article)
{
    $comic_categories = App\Category::comic();
    //$comic_url = route('avbodies.show', ['id' => $article->ez_map[0]->unique_id]);
    if (array_key_exists($article->category_id, $comic_categories->toArray())) {
        return url("/");
    }// dd('abc');
}
//不要那 enl 的文章 跟 comic 有關連
function check_god_show($article)
{
    $god_categories = App\Category::comic();
    //$comic_url = route('avbodies.show', ['id' => $article->ez_map[0]->unique_id]);
    if (array_key_exists($article->category_id, $god_categories->toArray())) {
        return url("/");
    }// dd('abc');
}
function check_dark_show($article)
{
    $dark_categories = App\Category::dark();
    $dark_url = route('darks.show', ['id' => $article->ez_map[0]->unique_id]);
//dd($dark_categories->toArray());
    if (array_key_exists($article->category_id, $dark_categories->toArray())) {
        //dd($article->category_id);
        return $dark_url;
        //header('Location: ' .$dark_url, true);
        //  return redirect($dark_url);
    }// dd('abc');
}

function animation_resize($source, $new_source, $width, $height)
{
    exec("gifsicle --colors 256 --resize " . $width . "x" . $height . "  -i $source > " . $new_source);
    /*
 $image = $image->coalesceImages();

  //$image->setCompressionQuality(40);
  //$i=0;
  foreach ($image as $frame) {

      $frame->setImageCompressionQuality(50);
      $frame->thumbnailImage($size_w, $size_h);
      $frame->setImagePage($size_w, $size_h, 0, 0);

  }
  $image = $image->deconstructImages();
  $image->writeImages($new_source, true);
  */
}

function animation($id, $source, $stand = 225)
{
    set_time_limit(0);
    $result = new stdClass();
    try {
        $dest_dir = public_path() . '/animation_photos/';
        $preview_dir = public_path() . '/animation_photos/preview/';

        //dd($source);
        $image = new \Imagick($source);
        //$ext=strtolower($image->getImageFormat());
        $exts = (explode(".", $source));
        $ext = strtolower(end($exts));
        $new_source = $dest_dir . $id . "." . $ext;

        list($width, $height) = getimagesize($source);
        // $width = $image->getImageWidth();
        //$height = $image->getImageHeight();
        //dd([$width, $height]);
        //exec("gifsicle --resize 225x302  -i $source > ".$new_source);die;
        //$stand=225;
        if (($width < $stand or $height < $stand) or File::size($source) / 1024 / 1024 > 8) {
            //放大圖片
            if ($width < $stand or $height < $stand) {
                if ($width < $height) {
                    //width 不滿250
                    $size_w = $stand;
                    $size_h = round(($stand / $width) * $height);
                }
                if ($width > $height) {
                    //height 不滿250
                    $size_h = $stand;
                    $size_w = round(($stand / $height) * $width);
                }
                if ($width == $height) {
                    $size_h = $size_w = $stand;
                }
                animation_resize($source, $new_source, $size_w, $size_h);
                // dd($width."-".$height."-".$source);
            } elseif (($width > $stand and $height > $stand) and File::size($source) / 1024 / 1024 > 8) {
                if ($width < $height) {
                    //width 比較小
                    $size_w = $stand;
                    $size_h = round(($stand / $width) * $height);
                }
                if ($width > $height) {
                    //height 比較小
                    $size_h = $stand;
                    $size_w = round(($stand / $height) * $width);
                }
                if ($width == $height) {
                    $size_h = $size_w = $stand;
                }
                animation_resize($source, $new_source, $size_w, $size_h);
            }
            //$image->writeImages($new_source, true);
            list($width, $height) = getimagesize($new_source);
            //dd($width."-".$height."-".$new_source);

            $mp4_file = $dest_dir . $id . ".mp4";
            $mp4_png = $preview_dir . $id . ".png";


            if (false != $image && "GIF" == $image->getImageFormat() && File::size($new_source) / 1024 / 1024 > 1000) {
                $image = $image->coalesceimages();
                $width = $image->getImageWidth();
                $height = $image->getImageHeight();

                foreach ($image as $gif) {
                    $width = max($gif->getImageWidth(), $width);
                    $height = max($gif->getImageHeight(), $height);
                }
                if ($height % 2 == 1) $height = $height - 1;
                if ($width % 2 == 1) $width = $width - 1;

                $command = "ffmpeg -i " . $new_source . " -s " . $width . ":" . $height;
                $command .= " -r 24 -c:v libx264 -c:a copy -pix_fmt yuv420p -f mp4 ";
                $command .= $mp4_file . " 2>&1";
                //  ffmpeg -f gif -i 108.gif 108.mp4
                //   dd($command);
                exec($command, $output);
                //刪掉source
                $image->destroy();
                File::delete($new_source);
                $result->width = $width;
                $result->height = $height;
                $result->size = filesize($mp4_file) / 1024;
                $result->source = $id . ".mp4";

                $command_png = "ffmpeg -i " . $mp4_file . " -ss 00:00:01.000 -vframes 1 " . $mp4_png;

                exec($command_png, $output);

                // dd($result);
                //echo print_r($output, true);
            } elseif ("GIF" == $image->getImageFormat()) {
                //採用 gif ，順便將附檔名正名 gif
                $ext = pathinfo($source, PATHINFO_EXTENSION);

                $result->source = $id . ".gif";
                $result->width = $width;
                $result->height = $height;
                $result->size = File::size($new_source) / 1024;
                // dd('用 gif ，順便將附檔名正名 gif');
            } else {
                //非gif 圖，不做處理
                $ext = pathinfo($source, PATHINFO_EXTENSION);
                $result->source = $id . "." . $ext;
                File::copy($source, $new_source);
                list($result->width, $result->height) = getimagesize($source);
                $result->size = File::size($source) / 1024;
                // dd('非gif 圖，不做處理');
            }


        } else {
            //不做處理
            $ext = pathinfo($source, PATHINFO_EXTENSION);
            $result->source = $id . "." . $ext;
            File::copy($source, $new_source);
            list($result->width, $result->height) = getimagesize($source);
            $result->size = File::size($source) / 1024;
            //  dd('不做處理');
        }
        //dd($result);

    } catch (Exception $e) {
        echo $e->getMessage();
    }
    return $result;
}

function mp4($image)
{

}

function facebookDebugger($url)
{
    $graph = 'https://graph.facebook.com/';
    $post = 'id=' . urlencode($url) . '&scrape=true';

    $r = curl_init();
    curl_setopt($r, CURLOPT_URL, $graph);
    curl_setopt($r, CURLOPT_POST, 1);
    curl_setopt($r, CURLOPT_POSTFIELDS, $post);
    curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($r, CURLOPT_CONNECTTIMEOUT, 5);
    $data = curl_exec($r);
    curl_close($r);
    return $data;
}
function string_optimize($string){
    $string = html_entity_decode($string);
    $string = htmlentities($string);

    $string = str_replace(' ', '-', $string);

    return $string;

}
function hyphenize($string)
{
    $string = html_entity_decode($string);
    $string = htmlentities($string);

    $string = str_replace(' ', '-', $string);

    return html_entity_decode($string);
    return $string;

}


/*
    若有下列字元，在某些格式下，必須經過編碼處理，以免發生錯誤
    # % { } | \ ^ ~ [ ] `<>空白 
    依據RFC1630、RFC1738、RFC2141、RFC2732、RFC2396、RFC3986，URL中有下列保留字
    ()‘!,[]+$= ;/#?:@&% 空白
*/
function title_optimize($title)
{
    $title = str_replace("%", "％", $title);
    $title = str_replace("/", "／", $title);
    $title = str_replace("#", "＃", $title);
    $title = str_replace("?", "？", $title);
    $title = str_replace("&", "＆", $title);
    return $title;
}


function arrayToObject($d)
{
    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return (object)array_map(__FUNCTION__, $d);
    } else {
        // Return object
        return $d;
    }
}


function replace_alt($html_content, $alt_text = '')
{

    //ifrmae外面 包itemprop
    $amps = article_amp_content($html_content);
    $html_content = preg_replace('#(<iframe(.*?)></iframe>)#', ' <div itemprop="video" itemscope itemtype="http://schema.org/VideoObject">' . '$1' . '</div>', $html_content);
//dd($html_content);
    $count = preg_match_all('/<img[^>]+>/i', $html_content, $images);

    if ($count === 0) return $html_content;
    $amp_html = '';
    foreach ($amps as $k => $amp) {
        $amp_html = '   <amp-img src="' . $amp["src"] . '" alt="' . $alt_text . '-' . $k . '" height="' . $amp["width"] . '" width="' . $amp["height"] . '"></amp-img>';

    }
    $o = $html_content;
    //圖片外面 包itemprop
    $o = preg_replace('#(<img [^>]+)#', ' <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">' . '$1' . '></div', $o);

    /**
     * 0 => array:3 [▼
     * 0 => "<img id="aimg_VVY6v" class="zoom" src="http://s2.imgs.cc/img/6eXWZtqt.jpg" alt="" width="750" height="410" border="0" />"
     * 1 => "<img id="aimg_RSVHN" class="zoom" src="http://s3.imgs.cc/img/LxDbdZe.jpg" alt="" width="480" border="0" />"
     * 2 => "<img id="aimg_JdKZt" class="zoom" src="http://s3.imgs.cc/img/7YsZEkG.jpg" alt="" width="607" border="0" />"
     * ]
     */
    foreach ($images[0] as $i => $img) {
        $t = 'alt="' . $alt_text . "-" . $i . '" ';
        $is_alt = preg_match_all('/ alt="([^"]*)"/i', $img, $alt);
        if ($is_alt == 0) {
            $new_img = str_replace('<img ', '<img ' . $t, $img);
            $o = str_replace($img, $new_img, $o);
        } elseif ($is_alt == 1) {
            $text = trim($alt[1][0]);
            if (empty($text)) {
                $new_img = str_replace(trim($alt[0][0]), $t, $img);
                $o = str_replace($img, $new_img, $o);
            }
        }
    }
    //micro 加 url

    $pattern = '/src="([^"]*)"/i';
    $replace = ' itemprop="url" src="$1"';
    $o = preg_replace($pattern, $replace, $o);

    //micro 加 width height
    $pattern = '/src=("[^"]*")(.*width)="([^"]*)"([^>]*)/i';
    $replace = 'src=$1$2="$3"$4>' . '<meta itemprop="width" content="$3"/';
    $o = preg_replace($pattern, $replace, $o);

    $pattern = '/src=("[^"]*")(.*height)="([^"]*)"[^>]*/i';
    $replace = 'src=$1$2="$3"$4>' . '<meta itemprop="height" content="$3"/';
    $o = preg_replace($pattern, $replace, $o);

    $o = $amp_html . $o;

    return $o;
}

// 文章 content
function article_handle_content($content)
{

    $content = cdn_handle($content);

    //lazyload 
    //$content= preg_replace("#(<img[^>]*)\ssrc=([^>]+>)#", '$1 data-original= $2', $content);

    // 移除 img 的 寬高 
    $content = preg_replace("#(<img[^>]+style=\"[^\"]*)width:[^;]+;*([^>]+>)#", '$1 $2', $content);
    $content = preg_replace("#(<img[^>]+style=\"[^\"]*)height:[^;]+;*([^>]+>)#", '$1 $2', $content);

    // 針對 peer 處理 移除 srcset 因為裡面有一堆 peer的資訊 
    $content = preg_replace("#(<img[^>]+)srcset=\"[^\"]+\"([^>]+>)#", '$1 $2', $content);

    // 讓換行好看一點  增加 p  tag 
    $content = wwpautop($content);


    return $content;

}


function cdn_handle($content)
{

    if (!defined('__DOMAIN__')) return $content;

    if (strpos(__DOMAIN__, 'eznewlife.com') === false) return $content;


    //preg_match_all('/<img[^>]*\ssrc="([^>]+)">/iU', $content, $match);


    $content = str_replace('/eznewlife.com/focus_photos/', '/cdn.eznewlife.com/focus_photos/', $content);
    $content = str_replace('/admin.eznewlife.com/focus_photos/', '/cdn.eznewlife.com/focus_photos/', $content);
    $content = str_replace('/dark.eznewlife.com/focus_photos/', '/cdn.eznewlife.com/focus_photos/', $content);
    $content = str_replace('/demo.eznewlife.com/focus_photos/', '/cdn.eznewlife.com/focus_photos/', $content);

    $content = str_replace('/eznewlife.com/wp-content/uploads/', '/cdn.eznewlife.com/wp-content/uploads/', $content);
    $content = str_replace('/admin.eznewlife.com/wp-content/uploads/', '/cdn.eznewlife.com/wp-content/uploads/', $content);
    $content = str_replace('/dark.eznewlife.com/wp-content/uploads/', '/cdn.eznewlife.com/wp-content/uploads/', $content);
    $content = str_replace('/demo.eznewlife.com/wp-content/uploads/', '/cdn.eznewlife.com/wp-content/uploads/', $content);

    $content = str_replace('/eznewlife.com/uploads/', '/cdn.eznewlife.com/uploads/', $content);
    $content = str_replace('/admin.eznewlife.com/uploads/', '/cdn.eznewlife.com/uploads/', $content);
    $content = str_replace('/dark.eznewlife.com/uploads/', '/cdn.eznewlife.com/uploads/', $content);
    $content = str_replace('/demo.eznewlife.com/uploads/', '/cdn.eznewlife.com/uploads/', $content);
    $content = str_replace('"/comic/images/', '"//cdn.eznewlife.com/comic/images/', $content);

    //preg_match_all('/<img[^>]*\ssrc="([^>]+)">/', $content, $match);
    preg_match_all('#<img[^>]*\ssrc="([^>]+)"#', $content, $match);

    if (isset($match[1])) {
        $ms = $match[1];
        for ($i = 0, $imax = count($ms); $i < $imax; $i++) {
            $m = $ms[$i];
            //echo $m."\n";
            if (strpos($m, 'http') !== false) continue;
            if (substr($m, 0, 2) === '//') continue;

            $r = $m;

            if (substr($m, 0, 1) !== '/') $r = '/' . $m;


            //$r = 'http://cdn.eznewlife.com' . $r;
            //$content = str_replace($m, $r, $content);

        }
    }


    //preg_match_all('#<img[^>]*\ssrc="([^>]+)"#', $content, $match);
    //print_r($match);

    return $content;
}


function content_paging_KILL($article)
{
    /**
     *
     * content 目前頁數顯示內容
     * content_page 目前頁數 ex:?page=2
     * content_count 總共頁數 ex:3
     * content_pages 所有分頁內容 ex :$content_pages[0],$content_pages[1],,$content_pages[2]
     */


    $article->content_pages = explode("@enl@", $article->content);
    $article->content_count = count($article->content_pages);
    $data = Input::all();
    //目前分頁
    if (!isset($data['page']) or ($data['page'] > $article->content_count)) {
        $data['page'] = 1;
    }
    //dd([$data,$article->content_count]);
    $content_page_key = $data['page'] - 1;
    //目前分頁內容
    $article->content_current = $article->content = $article->content_pages[$content_page_key];
    //目前分頁
    $article->content_page = $data['page'];
    return $article;
}


function content_paging($article)
{
//dd($article);
    if (is_string($article)) {
        $cp = make_content_paging($article);
        return $cp;
    }
    $cp = make_content_paging($article->content, $article->title);

    $article->content_pages = $cp->content_pages;
    $article->content_count = $cp->content_count;
    $article->content_current = $article->content = $cp->current_content;
    //$article->content=CloseTags($article->content);
    $article->content_page = $cp->page;
    $article->prev_page = $cp->prev_page;
    $article->next_page = $cp->next_page;
    $article->page_bar = $cp->page_bar;
    $article->page_bar_s = $cp->page_bar_s;
    $article->page_bar_scroll = $cp->page_bar_scroll;
    // dd($article);
    return $article;
}


function make_content_paging($content, $title = false)
{
    $cp = new __content_paging($content, $title);
    return $cp;
}


class __content_paging
{


    public function __construct($content, $title = false)
    {
        $this->content = $content;
        $this->title = $title;
        //$this->content = str_replace("@enl@",'',$this->content);
        $this->content_pages = explode("@enl@", $this->content);
        $this->content_count = count($this->content_pages);

        $this->page();
        $this->prev_page();
        $this->next_page();
        $this->page_bar();
        $this->page_bar_s();
        $this->page_bar_scroll();
        $this->seo_meta();
        $this->current_content = $this->current_content();

    }


    public function current_content()
    {
        return $this->content_pages[$this->page - 1];
    }

    public function page_bar()
    {


        $bar = '';
        if ($this->content_count > 1) {

            $bar .= '<nav class="text-center article-page" ><ul class="pagination" ><li class="previous  ';
            if ($this->prev_page == null) $bar .= 'disabled';
            $bar .= '" > <a href = "';
            if ($this->prev_page == null) $bar .= '#'; else $bar .= '?page=' . $this->prev_page;
            $bar .= '" aria-label = "Previous" ><span aria-hidden = "true" >&laquo;上一頁 </span > </a > </li>' . "\n";


            for ($i = 1; $i <= $this->content_count; $i++) {
                $bar .= ' <li class="';
                if ($this->page == $i) $bar .= 'active';
                $bar .= '" ><a href = "?page=' . $i . '" >' . $i . '' . "\n";
            }
            $bar .= '</li>';

            $bar .= '<li class="next  ';
            if ($this->next_page == null) $bar .= 'disabled';
            $bar .= '" > <a href = "';
            if ($this->next_page == null) $bar .= '#'; else $bar .= '?page=' . $this->next_page;
            $bar .= '" aria-label = "Next" ><span aria-hidden = "true" >下一頁&raquo;  </span > </a > </li>' . "\n";


        }
        $this->page_bar = $bar;
        return;


    }

    public function page_bar_scroll()
    {

        $bar = '';

        if ($this->content_count > 1) {
            /*
                $bar .= '<nav class="text-center article-page" ><ul class="pagination" ><li class="previous  ';
                if ($this->prev_page == null) $bar .= 'disabled';
                $bar .= '" > <a href = "';
                if ($this->prev_page == null) $bar .= '#'; else $bar .= '?page=' . $this->prev_page;
                $bar .= '" aria-label = "Previous" ><span aria-hidden = "true" > « </span > </a > </li>'."\n";*/
            //echo "<h1>".$this->content_count."</h1>";
            $bar .= '<form class="center-block" style="width:280px"> <div class="input-group">';
            //  $bar.='<div class="input-group-btn"><a hrfe="" class="btn btn-default"><span class="glyphicon glyphicon-bold"></span></a>';
            $bar .= '<span class="input-group-btn"><a class="btn btn-default btn-lg bg-black  ';
            if ($this->prev_page == null) $bar .= ' disabled ';

            if ($this->prev_page == 1) {
                $tmp=Request::url();
            }else{
                $tmp='?page=' . $this->prev_page;
            }
            $bar .= '" href="'
                .$tmp
                . '"  ' . 'alt="' . $this->title . ' - 第' . $this->prev_page . '頁" rel="prev">上一頁</a>'
                . ' </span>';
            // dd([$this->prev_page,$this->page]);
            if ($this->next_page == null and Route::currentRouteName() == 'avbodies.show') {
                $bar .= '<span class="input-group-btn"><a style="color:white !important;" class="btn btn-warning btn-lg ';
                $bar .= '" href="'
                    . url("/")
                    . '"' . 'alt="' . ' AvBody" ' . ' rel="first">回首頁</a>'
                    . ' </span>';
            } else {
                $bar .= '<select class="selectpicker form-control input-lg" data-live-search="true"  onchange="location = this.value;">';
                for ($i = 1; $i <= $this->content_count; $i++) {
                    $bar .= ' <option ';
                    if ($this->page == $i) $bar .= ' selected ';
                    if ($i==1){
                        $bar .= 'value="'.Request::url().'" >' . $i . '</option>' . "\n";
                    } else {
                        $bar .= 'value="?page=' . $i . '" >' . $i . '</option>' . "\n";
                    }
                }
                $bar .= '</select>';
                $bar .= '<span class="input-group-btn"><a class="btn btn-default btn-lg bg-black ';
                if ($this->next_page == null) $bar .= ' disabled ';
                if ($this->next_page == 1) {
                    $tmp=Request::url();
                }else{
                    $tmp='?page=' . $this->next_page;
                }
                $bar .= '" href="'
                    . $tmp
                    . '"  ' . 'alt="' . $this->title . ' - 第' . $this->next_page . '頁"  rel="prev">下一頁</a>'
                    . ' </span>';
            }
            $bar .= '  </div></form>';
            /*
            $bar .= '<li class="next  ';
            if ($this->next_page == null) $bar .= 'disabled';
            $bar .= '" > <a href = "';
            if ($this->next_page == null) $bar .= '#'; else $bar .= '?page=' . $this->next_page;
            $bar .= '" aria-label = "Next" ><span aria-hidden = "true" >»</span> </a > </li>'."\n";
            $bar .= '</ul></nav>';*/


        }
        $this->page_bar_scroll = $bar;
        return;


    }

    public function page_bar_s()
    {
        $bar = '';
        if ($this->content_count > 1) {

            $bar .= '<nav class="text-center article-page" ><ul class="pagination" ><li class="previous  ';
            if ($this->prev_page == null) $bar .= 'disabled';
            $bar .= '" > <a href = "';
            if ($this->prev_page == null) $bar .= '#'; else $bar .= '?page=' . $this->prev_page;
            $bar .= '" aria-label = "Previous" ><span aria-hidden = "true" > « </span > </a > </li>' . "\n";
            //echo "<h1>".$this->content_count."</h1>";
            $countdown_three = $this->content_count - 3;
            if ($this->page <= 4) {
                $start = 1;
                if ($this->content_count < 7) {
                    $end = $this->content_count;
                } else {
                    $end = 7;
                }
            } else if ($this->page > $countdown_three) {
                if ($this->content_count < 7) {
                    $start = 1;
                } else {
                    $start = $this->content_count - 7;
                }

                $end = $this->content_count;
            } else {
                $start = $this->page - 3;
                $end = $this->page + 3;
            }
            if ($this->content_count > 9) {

                for ($i = 1; $i <= 2; $i++) {
                    if ($this->page > ($i + 3)) {
                        $bar .= ' <li class="';
                        if ($this->page == $i) $bar .= 'active';
                        $bar .= '" ><a href = "?page=' . $i . '" >' . $i . '</a ></li >' . "\n";
                    }
                }

                if ($this->page > 6) $bar .= '<li class="disabled"><span>...</span></li>';
            }

            for ($i = $start; $i <= $end; $i++) {
                $bar .= ' <li class="';
                if ($this->page == $i) $bar .= 'active';
                $bar .= '" ><a href = "?page=' . $i . '" >' . $i . '</a ></li >' . "\n";
            }
            if ($this->page < $countdown_three) {
                $s = $this->content_count - 1;
                if ($s > ($end + 1)) $bar .= '<li class="disabled"><span>...</span></li>';
                for ($i = $s; $i <= $this->content_count; $i++) {
                    if ($i + 1 > ($end + 1)) {
                        $bar .= ' <li class="';

                        if ($this->page == $i) $bar .= 'active';
                        $bar .= '" ><a href = "?page=' . $i . '" >' . $i . '</a ></li >' . "\n";
                    }
                }
            }
            $bar .= '<li class="next  ';
            if ($this->next_page == null) $bar .= 'disabled';
            $bar .= '" > <a href = "';
            if ($this->next_page == null) $bar .= '#'; else $bar .= '?page=' . $this->next_page;
            $bar .= '" aria-label = "Next" ><span aria-hidden = "true" >»</span> </a > </li>' . "\n";
            $bar .= '</ul></nav>';


        }
        $this->page_bar_s = $bar;
        return;


    }

    public function page()
    {
        $this->page = Input::get('page');

        if (is_numeric($this->page)) {

            $this->page = $this->page - 0;

            if ($this->page < 1) $this->page = 1;

            if ($this->page > $this->content_count) {
                $this->page = $this->page = 1;
            }
            return;
        }

        $this->page = 1;
        return;
    }

    public function prev_page()
    {

        $this->prev_page = $this->page - 1;
        if ($this->prev_page < 1) $this->prev_page = null;

        return;
    }

    public function next_page()
    {

        $this->next_page = $this->page + 1;

        if ($this->next_page > $this->content_count) $this->next_page = null;
        return;
    }

    public function seo_meta()
    {

        if ($this->page === 1) $meta = '';
        $meta = '<link rel="canonical" href="' . Request::url() . '?page=' . $this->page . '" />';
        if (!empty($this->next_page)) {
            $meta .= '<link rel="next" href="' . Request::url() . '?page=' . $this->next_page . '" />';
        }
        if (!empty($this->prev_page)) {
            $meta .= '<link rel="prev" href="' . Request::url() . '?page=' . $this->prev_page . '" />';
        }
        $this->seo_meta = $meta;
        // return $meta;

    }
}

class gifmaker
{
    // 初始化
    function __construct()
    {

    }

    //圖層
    public function load($url)
    {
        $this->frame[] = $url;
        // return $this;
    }

    public function save($gif_path, $loop = 100)
    {
        if (!isset($this->frame) or !is_array($this->frame) or empty($this->frame)) return "no load";//fail
        $GIF = new Imagick();

        foreach ($this->frame as $i => $url) {
            //handle = fopen($file, 'rb');
            $image = file_get_contents($url);
            $frame = new Imagick();
            // $frame->readImage($file);
            $frame->readImageBlob($image);
            $frame->setImageDispose(2);
            $frame->setImageDelay($loop);
            // $GIF->setImageDelay(5);
            $GIF->addImage($frame);
        }
        $GIF->optimizeImageLayers();
        //file multi.TIF
        $GIF->writeImages($gif_path, true);
        return 1;

    }

}

function wwpautop($pee, $br = true)
{

    $pre_tags = array();

    if (trim($pee) === '')
        return '';

    // Just to make things a little easier, pad the end.
    $pee = $pee . "\n";

    /*
     * Pre tags shouldn't be touched by autop.
     * Replace pre tags with placeholders and bring them back after autop.
     */
    if (strpos($pee, '<pre') !== false) {
        $pee_parts = explode('</pre>', $pee);
        $last_pee = array_pop($pee_parts);
        $pee = '';
        $i = 0;

        foreach ($pee_parts as $pee_part) {
            $start = strpos($pee_part, '<pre');

            // Malformed html?
            if ($start === false) {
                $pee .= $pee_part;
                continue;
            }

            $name = "<pre wp-pre-tag-$i></pre>";
            $pre_tags[$name] = substr($pee_part, $start) . '</pre>';

            $pee .= substr($pee_part, 0, $start) . $name;
            $i++;
        }

        $pee .= $last_pee;
    }
    // Change multiple <br>s into two line breaks, which will turn into paragraphs.
    $pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);

    $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';

    // Add a single line break above block-level opening tags.
    $pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);

    // Add a double line break below block-level closing tags.
    $pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);

    // Standardize newline characters to "\n".
    $pee = str_replace(array("\r\n", "\r"), "\n", $pee);

    // Find newlines in all elements and add placeholders.
    $pee = wp_replace_in_html_tags($pee, array("\n" => " <!-- wpnl --> "));

    // Collapse line breaks before and after <option> elements so they don't get autop'd.
    if (strpos($pee, '<option') !== false) {
        $pee = preg_replace('|\s*<option|', '<option', $pee);
        $pee = preg_replace('|</option>\s*|', '</option>', $pee);
    }

    /*
     * Collapse line breaks inside <object> elements, before <param> and <embed> elements
     * so they don't get autop'd.
     */
    if (strpos($pee, '</object>') !== false) {
        $pee = preg_replace('|(<object[^>]*>)\s*|', '$1', $pee);
        $pee = preg_replace('|\s*</object>|', '</object>', $pee);
        $pee = preg_replace('%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee);
    }

    /*
     * Collapse line breaks inside <audio> and <video> elements,
     * before and after <source> and <track> elements.
     */
    if (strpos($pee, '<source') !== false || strpos($pee, '<track') !== false) {
        $pee = preg_replace('%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee);
        $pee = preg_replace('%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee);
        $pee = preg_replace('%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee);
    }

    // Remove more than two contiguous line breaks.
    $pee = preg_replace("/\n\n+/", "\n\n", $pee);

    // Split up the contents into an array of strings, separated by double line breaks.
    $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);

    // Reset $pee prior to rebuilding.
    $pee = '';

    // Rebuild the content as a string, wrapping every bit with a <p>.
    foreach ($pees as $tinkle) {
        $pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
    }

    // Under certain strange conditions it could create a P of entirely whitespace.
    $pee = preg_replace('|<p>\s*</p>|', '', $pee);

    // Add a closing <p> inside <div>, <address>, or <form> tag if missing.
    $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);

    // If an opening or closing block element tag is wrapped in a <p>, unwrap it.
    $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

    // In some cases <li> may get wrapped in <p>, fix them.
    $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee);

    // If a <blockquote> is wrapped with a <p>, move it inside the <blockquote>.
    $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
    $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);

    // If an opening or closing block element tag is preceded by an opening <p> tag, remove it.
    $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);

    // If an opening or closing block element tag is followed by a closing <p> tag, remove it.
    $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

    // Optionally insert line breaks.
    if ($br) {
        // Replace newlines that shouldn't be touched with a placeholder.
        $pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', '_autop_newline_preservation_helper', $pee);

        // Replace any new line characters that aren't preceded by a <br /> with a <br />.
        $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee);

        // Replace newline placeholders with newlines.
        $pee = str_replace('<WPPreserveNewline />', "\n", $pee);
    }

    // If a <br /> tag is after an opening or closing block tag, remove it.
    $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);

    // If a <br /> tag is before a subset of opening or closing block tags, remove it.
    $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
    $pee = preg_replace("|\n</p>$|", '</p>', $pee);

    // Replace placeholder <pre> tags with their original content.
    if (!empty($pre_tags))
        $pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);

    // Restore newlines in all elements.
    $pee = str_replace(" <!-- wpnl --> ", "\n", $pee);


    $pee = str_replace(']]>', ']]&gt;', $pee);

    return $pee;
}

//不太有用 放到後面去
function closetags($html)
{
    preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
    $openedtags = $result[1];
    preg_match_all('#</([a-z]+)>#iU', $html, $result);
    $closedtags = $result[1];
    $len_opened = count($openedtags);
    if (count($closedtags) == $len_opened) {
        return $html;
    }
    $openedtags = array_reverse($openedtags);
    for ($i = 0; $i < $len_opened; $i++) {
        if (!in_array($openedtags[$i], $closedtags)) {
            $html .= '</' . $openedtags[$i] . '>';
        } else {
            unset($closedtags[array_search($openedtags[$i], $closedtags)]);
        }
    }
    return $html;

}




function replace_eznewlife($content){

    
    if(secure()){
        $content = str_replace('http://cdn.eznewlife.com', 'https://eznewlife.com', $content);
        $content = str_replace('http://eznewlife.com', 'https://eznewlife.com', $content);
        $content = str_replace('http://getez.info', 'https://getez.info', $content);
    }


    $content = str_replace('<meta property="og:url" content="https',  '<meta property="og:url" content="http', $content);


    if( strpos(__DOMAIN__, 'getez.info') === false ) return $content;


    $content = str_replace('//cdn.eznewlife.com', '//getez.info', $content);
    $content = str_replace('//eznewlife.com', '//getez.info', $content);
    $content = str_replace('//dark.eznewlife.com', '//dark.getez.info', $content);
    $content = str_replace('dark.eznewlife.com', 'dark.getez.info', $content);





    return $content;

} 





function cache_forever($prop, $value){
    

    return Cache::forever($prop, $value);


    static $conn=null;    

    if($conn===null) $conn = oconn('M');


    $content = serialize($value);

    $sql="select id from filedb where prop=:prop";
    $row = $conn->getOne($sql, $prop);

    if($row === false){
        $conn->insert("INSERT INTO filedb SET prop=:prop, content=:content", $prop, $content);
        return;
    }


    $conn->update("UPDATE filedb SET content=:content WHERE prop=:prop", $content, $prop);


    //if($i%1000 === 0) echo $i."\n";

    return Cache::forever($prop, $value);
}






function cache_has($prop){
    return Cache::has($prop);
}





function cache_forget($prop){
    return Cache::forget($prop);
}





function cache_get($prop){

    if(isset($_COOKIE['debug'])){
        //echo "<!--".$prop."-->\n";
    }

    return Cache::get($prop);
}




function cache_put($prop, $value, $expire){
    Cache::put($prop, $value, $expire);
}





function timer(){
    static $t=null;

    if($t===null){
        $t = microtime(true);
        return 0;
    }


    $now = microtime(true);
    
    return (($now - $t)*1000);
}
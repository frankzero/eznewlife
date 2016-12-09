<?php 

/*********************************************
 * dark.eznewlife.com
 ***********************************************/


Route::group(['domain' => 'dark.eznewlife.com'], function () {
    Route::get('mysitemap', function () {
        $sitemap = App::make("sitemap");
        File::makeDirectory(public_path() . '/dark_sitemap', 0777, true, true);
        $article = App\Article::publish()->dark()->with('author', 'ez_map')->orderBy("id", "asc")->paginate(5000);
        if (!isset($_GET['page']) or $_GET['page'] == 1) $page = 1; else $page = $_GET['page'];
        foreach ($article as $key => $article) {
            $url = route('darks.show', ['id' => $article->ez_map[0]->unique_id]);
            $sitemap->add($url, $article->updated_at, 0.7, 'daily');
        }
        $sitemap->store('xml', 'dark_sitemap/sitemap_' . $page);
        echo '<a href="dark_sitemap/sitemap_' . $page . '.xml">' . $article->count() . '--' . $page . '</a>';
    });
    Route::get('/', ['uses' => 'DarkController@index', 'as' => 'darks.index']);
    Route::get('{id}', ['uses' => 'DarkController@show', 'as' => 'darks.show'])
        ->where(['id' => '[0-9]+']);
    Route::get('rand', ['uses' => 'DarkController@rand', 'as' => 'darks.rand']);
    Route::get('rand_app', ['uses' => 'DarkController@rand_app', 'as' => 'darks.rand_app']);
    Route::get('tag/{name}', ['uses' => 'DarkController@tag', 'as' => 'darks.tag']);
    Route::get('ajax/{page?}', ['uses' => 'DarkController@ajax', 'as' => 'darks.ajax']);
    Route::get('category/{id}/{name?}', ['uses' => 'DarkController@category', 'as' => 'darks.category'])->where(['id' => '[0-9]+']);
    Route::any('search', ['uses' => 'DarkController@search', 'as' => 'darks.search']);
    /**enl隨機文章*/
        Route::get('/404notfound', ['uses' => 'DarkController@notfound', 'as' => 'darks.notfound']);


    Route::post('sm', function(){
         
        if (!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }elseif(!empty($_SERVER["HTTP_X_FORWARDED"])){
            $ip = $_SERVER["HTTP_X_FORWARDED"];
        }elseif(!empty($_SERVER["HTTP_FORWARDED_FOR"])){
            $ip = $_SERVER["HTTP_FORWARDED_FOR"];
        }elseif(!empty($_SERVER["HTTP_FORWARDED"])){
            $ip = $_SERVER["HTTP_FORWARDED"];
        }else{
            $ip = $_SERVER["REMOTE_ADDR"];
        }



        $p = [];
        $p['server_ip'] = $_POST['server_ip'];
        $p['referer'] = $_POST['referer'];
        $p['tt'] = $_POST['tt'];
        $p['protocol'] = $_POST['protocol'];
        $p['host'] = $_POST['host'];
        $p['pathname'] = $_POST['pathname'];
        $p['querystring'] = http_build_query($_POST);
        $p['url'] = $_POST['url'];
        $p['ip'] = $ip;
        $p['user_agent'] = $_SERVER ['HTTP_USER_AGENT'];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://59.126.180.51:90");
        curl_setopt($ch, CURLOPT_POST, true); // 啟用POST
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER , true); 
        //curl_setopt($ch, CURLOPT_HEADER          , false); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $p )); 
        curl_exec($ch);
        curl_close($ch);

        exit;
    });

});

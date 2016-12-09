<?php 

/*********************************************
 * avbody.info
 ***********************************************/
/*
if (Request::server('SERVER_ADDR') == '59.126.180.51') {
    Route::group(['domain' => $_domain('avbody.info')], function () {
        Route::get('mysitemap', function () {
            $sitemap = App::make("sitemap");
            File::makeDirectory(public_path() . '/comic_sitemap', 0777, true, true);
            $article = App\Article::publish()->comic()->with('author', 'ez_map')->orderBy("id", "asc")->paginate(5000);
            if (!isset($_GET['page']) or $_GET['page'] == 1) $page = 1; else $page = $_GET['page'];
            foreach ($article as $key => $article) {
                $url = route('comics.show', ['id' => $article->ez_map[0]->unique_id]);
                $sitemap->add($url, $article->updated_at, 0.7, 'daily');
            }
            $sitemap->store('xml', 'comic_sitemap/sitemap_' . $page);
            echo '<a href="comic_sitemap/sitemap_' . $page . '.xml">' . $article->count() . '--' . $page . '</a>';
        });
        
        Route::get('/', ['uses' => 'ComicController@index', 'as' => 'comics.index']);
        Route::get('rand', ['uses' => 'ComicController@rand', 'as' => 'Comics.rand']);
        Route::get('rand_app', ['uses' => 'ComicController@rand_app', 'as' => 'comics.rand_app']);
        Route::get('ajax/{page?}', ['uses' => 'ComicController@ajax', 'as' => 'comics.ajax']);
        Route::get('{id}/{title?}/{page?}', ['uses' => 'ComicController@show', 'as' => 'comics.show'])
            ->where(['id' => '[0-9]+']);
        Route::any('search', ['uses' => 'ComicController@search', 'as' => 'comics.search']);
        Route::get('/404', function () {
            return view('comics/404')->with('plan', 2);
        });
    });
} else {*/
    Route::group(['domain' => $_domain('avbody.info')], function () {
        //$foo = new \App\AvUser();

        //echo  Auth::av_user()->getRememberToken();
        Route::get('mysitemap', function () {
            $sitemap = App::make("sitemap");
            File::makeDirectory(public_path() . '/comic_sitemap', 0777, true, true);
            $article = App\Article::publish()->comic()->with('author', 'ez_map')->orderBy("score", "desc")->paginate(5000);
            if (!isset($_GET['page']) or $_GET['page'] == 1) $page = 1; else $page = $_GET['page'];
            foreach ($article as $key => $article) {
                $url = route('avbodies.show', ['id' => $article->ez_map[0]->unique_id]);
                if ($article->score>70){
                    $sitemap->add($url, $article->publish_at, 0.7, 'daily');
                }elseif ($article->score>50) {
                    $sitemap->add($url, $article->publish_at, 0.5, 'daily');
                } else {
                    $sitemap->add($url, $article->publish_at, 0.3, 'daily');
                }
            }
            $sitemap->store('xml', 'comic_sitemap/sitemap_' . $page);
            echo '<a href="comic_sitemap/sitemap_' . $page . '.xml">' . $article->count() . '--' . $page . '</a>';
        });
        /***
        * AV User
        */
    
        // 會員專區
        Route::get('me/profile',['uses'=>'AvUserController@profile','as'=>'av.user.profile', 'middleware' => 'av_user'] );
        Route::get('me/collect',['uses'=>'AvUserController@collect','as'=>'av.user.collect', 'middleware' => 'av_user'] );
        Route::get('me/collect_articles',['uses'=>'AvUserController@collect_articles','as'=>'av.user.collect_articles', 'middleware' => 'av_user'] );

        Route::put('me/update', ['uses' => 'AvUserController@update', 'as' => 'av.user.update', 'middleware' => 'av_user']);
        Route::delete('me/article', ['uses' => 'AvUserController@destory', 'as' => 'av.user.article.delete', 'middleware' => 'av_user']);

        Route::post('me/check_repeat', ['uses' => 'AvUserController@check_repeat', 'as' => 'av.user.check_repeat']);
        
        Route::get('/', ['uses' => 'AvbodyController@index', 'as' => 'avbodies.index']);
        Route::get('rand', ['uses' => 'AvbodyController@rand', 'as' => 'avbodys.rand']);
        Route::get('rand_app', ['uses' => 'AvbodyController@rand_app', 'as' => 'avbodies.rand_app']);
        Route::get('collect/{id}', ['uses' => 'AvbodyController@collect', 'as' => 'avbodies.collect'])->where(['id' => '[0-9]+']);
        Route::post('vote/{id}', ['uses' => 'AvbodyController@vote', 'as' => 'avbodies.vote'])->where(['id' => '[0-9]+']);

        Route::get('{id}/{title?}/{page?}', ['uses' => 'AvbodyController@show', 'as' => 'avbodies.show'])
            ->where(['id' => '[0-9]+']);
        Route::any('search', ['uses' => 'AvbodyController@search', 'as' => 'avbodies.search']);
        Route::get('/404notfound', ['uses' => 'AvbodyController@notfound', 'as' => 'avbodies.notfound']);
        Route::get('privacy', ['uses' => 'PrivacyController@avbody', 'as' => 'avbodies.privacy']);
        Route::get('/tool', ['uses' => 'AvbodyController@tool', 'as' => 'avbodies.tool']);

        // 認證路由...
        Route::get('auth/login', ['uses' =>'Auth\AuthController@avbodyLogin', 'as' => 'avbodies.login']);
        //Route::post('auth/login', 'Auth\AuthController@postLogin');
        Route::get('auth/logout', ['uses'=>'Auth\AuthController@avbodyLogout','as'=>'avbodies.logout']);
        // 注册路由...
        Route::get('auth/register', 'Auth\AuthController@getRegister');
        Route::post('auth/register', 'Auth\AuthController@postRegister');
        //facebook
        Route::get('auth/facebook',['uses'=>'Auth\AuthController@redirectToProvider','as'=>'avbodies.facebook.login'] );
        Route::get('auth/facebook/callback', 'Auth\AuthController@handleProviderCallback');

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

//}


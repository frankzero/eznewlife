<?php 



// echo $_domain('eznewlife.com');exit;
Route::group(['domain' => $_domain('eznewlife.com')], function () {
    //ff\load_config('eznewlife.com');
    //if(!defined('__DOMAIN__')) define('__DOMAIN__', 'eznewlife.com');
    Route::any('articles/search', ['uses' => 'ArticleController@search', 'as' => 'articles.search']);

    Route::any('/googlesearch', ['uses' => 'ArticleController@googlesearch', 'as' => 'articles.googlesearch']);

    Route::get('/paged/{paged}', ['uses' => 'ArticleController@pageData'])->where('paged', '[0-9]+');

    Route::get('/test2', function(){
        
        
        if(!isset($_GET['uid'])) return '';
        $file = __APP__.'storage/frank/'.$_GET['uid'];

        if(file_exists($file)){
            return file_get_contents($file);
        }

        return '';
    });

    Route::get('/test', 'ArticleController@test');
    //**/
    Route::get('log', function () {
        $contents = "<pre>" . file_get_contents('/home/eznewlife/ad.eznewlife.com/laravel/storage/app/route.txt', true) . "</pre>";
        // $contents = Storage::get('route.txt');
        echo $contents;
    });
    Route::get('34log', function () {
        $contents = "<pre>" . file_get_contents('/home/eznewlife/ad.eznewlife.com/laravel/storage/app/34.route.txt', true) . "</pre>";
        // $contents = Storage::get('route.txt');
        echo $contents;
    });


    Route::get('enlapi', ['uses' => 'enlapiController@api', 'as' => 'enl.api']);


    Route::get('mysitemap', function () {

        $sitemap = App::make("sitemap");
        File::makeDirectory(public_path() . '/enl_sitemap', 0777, true, true);
        $article = App\Article::publish()->with('ez_map')->orderBy("id", "asc")->paginate(4000);
        if (!isset($_GET['page']) or $_GET['page'] == 1) {
            $sitemap->add('http://eznewlife.com', '2016-1-13T20:10:00+02:00', '1.0', 'daily');  //  $sitemap->add(URL::to(), '2015-01-13T12:30:00+02:00', '0.9', 'monthly');

            $categories = App\Category::getList();
            foreach ($categories as $id => $cate) {
                $url = route('articles.category', ['id' => $id, 'name' => $cate]);
                $url = "http://" . substr($url, strpos($url, ".") + 1);
                $sitemap->add($url, date("Y-m-d H:i:s"), 0.8, 'monthly');
            }
            //dd($article);

            // add items to the sitemap (url, date, priority, freq)

            $my_tags = App\Article::existingTags()->pluck('name')->all();
            foreach ($my_tags as $key => $name) {
                $url = route('articles.tag', ['name' => $name]);
                $url = "http://" . substr($url, strpos($url, ".") + 1);
                $sitemap->add($url, date("Y-m-d H:i:s"), 0.8, 'monthly');
            }
            $page = 1;
        } else $page = $_GET['page'];
        // add every post to the sitemap

        foreach ($article as $key => $article) {
            $url = route('articles.show', ['id' => $article->ez_map[0]->unique_id, 'title' => hyphenize($article->title)]);
            $url = "http://" . substr($url, strpos($url, ".") + 1);
            $sitemap->add($url, $article->updated_at, 0.7, 'daily');
        }

        // generate your sitemap (format, filename)
        //$sitemap->store('xml', 'sitemap_' . $page);
        $sitemap->store('xml', 'enl_sitemap/sitemap_' . $page);
        echo '<a href="enl_sitemap/sitemap_' . $page . '.xml">' . $article->count() . '--' . $page . '</a>';

        // this will generate file mysitemap.xml to your public folder

    });
    Route::get('/', ['uses' => 'ArticleController@index', 'as' => 'articles.index']);
    // 認證路由...
    Route::get('auth/login', 'Auth\AuthController@getLogin');
    Route::post('auth/login', 'Auth\AuthController@postLogin');
    Route::get('auth/logout', 'Auth\AuthController@getLogout');
    //facebook
    Route::get('auth/facebook',['uses'=>'Auth\AuthController@redirectToEnl','as'=>'enl.facebook.login'] );
    Route::get('auth/facebook/callback', 'Auth\AuthController@handleEnlCallback');
    Route::get('auth/facebook/logout', ['uses'=>'Auth\AuthController@enlLogout','as'=>'enl.logout']);
    Route::get('auth/facebook/login', ['uses' =>'Auth\AuthController@enlLogin', 'as' => 'enl.login']);
    // 註冊路由...
    Route::get('auth/register', 'Auth\AuthController@getRegister');
    Route::post('auth/register', 'Auth\AuthController@postRegister');
    // 密碼重置連結的路由...
    Route::get('password/email', 'Auth\PasswordController@getEmail');
    Route::post('password/email', 'Auth\PasswordController@postEmail');

    // 密碼重置的路由...
    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
    Route::post('password/reset', 'Auth\PasswordController@postReset');
    /*********************************************
     * 【後台管理】admin.eznewlife.com
     ***********************************************/
    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'user'], function () {

        if ($_SERVER['SERVER_NAME'] == "eznewlife.com" and strpos($_SERVER['REQUEST_URI'], "admin") == true) {
            header("Location: http://admin.eznewlife.com/admin");
            die();
        }
        Route::get('/', 'AdminHomeController@index');
        /**
         * 所有驗證式 最好在resourse 之前 防呆
         **/
        Route::controller('articles/datatables', 'ArticleController', [
            'postData' => 'datatables.articles.data',
            'getIndex' => 'datatables.articles'
        ]);
        /*後台cache*/

        Route::get('/cache/{i}', [ 'uses' => 'ArticleController@all_cache',  'as' => 'admin.all.cache']);
        Route::get('/articles/cache/{id}', ['uses' => 'ArticleController@save_article_cache', 'as' => 'admin.articles.cache']);
        Route::get('/articles/list', [ 'uses' => 'ArticleController@getList',  'as' => 'admin.articles.list']);
        Route::post('/articles/listData', 'ArticleController@listData');
        
        //後台 avuser
      // Route::get('av_users/ajax', 'AvuserController@manageAvUserAjax');
       Route::get('av_users/ajax', ['uses'=>'AvuserController@manageAvUserAjax','as'=>'admin.av_users.ajax']);
       // Route::get('av_users', ['uses' => 'AvuserController@index', 'as' => 'admin.av_users.index']);

        Route::post('av_users/datatables/data', 'AvuserController@postData');
        Route::controller('av_users/datatables', 'AvuserController', [
            'postData' => 'datatables.av_users.data',
            'getIndex' => 'datatables.av_users'
        ]); 
        Route::get('av_users/export',['uses'=>'AvuserController@export','as'=>'admin.av_users.export']);
        Route::get('av_users/analysis',['uses'=>'AvuserController@analysis','as'=>'admin.av_users.analysis']);
        Route::get('fb_lives/desc', ['uses'=>'FbLiveController@desc','as'=>'admin.fb_lives.desc']);

        Route::get('av/analysis',['uses'=>'AvAnalysisController@analysis','as'=>'admin.analysis']);
    
       Route::resource('av_users', 'AvuserController');
        /**
         * Fb Live 直播
         */

        Route::post('fb_lives/auto_save', ['uses'=>'FbLiveController@auto_save','as'=>'admin.fb_lives.auto_save']);
        Route::get('fb_lives/ajax', ['uses'=>'FbLiveController@manageFbLiveAjax','as'=>'admin.fb_lives.ajax']);
        Route::resource('fb_lives', 'FbLiveController');
        /**
         * 顯示log
         */
        Route::get('logs', ['uses' => 'LogController@index', 'as' => 'admin.logs.index']);
        Route::get('logs/content', ['uses' => '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index', 'as' => 'admin.logs.content']);

        Route::get('logs/datatables/data', 'LogController@postData');
        Route::controller('logs/datatables', 'LogController', [
            'postData' => 'datatables.logs.data',
            'getIndex' => 'datatables.logs'
        ]);
        /**廣告管理*/
        Route::get('/adv/editor/', 'advController@editor');
        Route::get('/adv/preview/', 'advController@preview');
        Route::get('/adv/editor/data', 'advController@editordata');
        Route::get('/adv/get_ad', 'advController@get_ad');
        Route::post('/adv/set_ad', 'advController@set_ad');
        /**
         * 文章部分
         */
        Route::post('/articles/update/{id}', ['uses' => 'ArticleController@update', 'as' => 'admin.articles.auto.update']);
        Route::put('/articles/update/{id}', ['uses' => 'ArticleController@update', 'as' => 'admin.articles.auto.update']);
        Route::post('/articles/auto_save', ['uses' => 'ArticleController@auto_save', 'as' => 'admin.articles.auto_save']);
        Route::post('/articles/upload', 'ArticleController@upload');
        Route::post('/articles/check_repeat', 'ArticleController@check_repeat');
        Route::get('articles/index_new', 'ArticleController@index_new');
        Route::get('articles/json', 'ArticleController@json');
        Route::post('/articles/instant/{id}', ['uses' => 'ArticleController@instant', 'as' => 'admin.articles.instant']);
        Route::put('/articles/instant/{id}', ['uses' => 'ArticleController@instant', 'as' => 'admin.articles.instant']);
        Route::resource('articles', 'ArticleController');
        /**/
        Route::get('newsletters/datatables/data', 'NewsletterController@postData');
        Route::controller('newsletters/datatables', 'NewsletterController', [
            'postData' => 'datatables.newsletters.data',
            'getIndex' => 'datatables.newsletters'
        ]);
        /**
         * 電子報部分
         */
        Route::post('/newsletters/update/{id}', ['uses' => 'NewsletterController@update', 'as' => 'admin.newsletters.auto.update']);
        Route::put('/newsletters/update/{id}', ['uses' => 'NewsletterController@update', 'as' => 'admin.newsletters.auto.update']);
        Route::post('newsletters/auto_save', 'NewsletterController@auto_save');
        Route::resource('newsletters', 'NewsletterController');

        /**動圖管理**/
        Route::get('animations/datatables/data', 'AnimationController@postData');
        Route::controller('animations/datatables', 'AnimationController', [
            'postData' => 'datatables.animations.data',
            'getIndex' => 'datatables.animations'
        ]);
        Route::post('/animations/reply/{id}', ['uses' => 'AnimationController@reply', 'as' => 'admin.animations.reply']);
        Route::put('/animations/reply/{id}', ['uses' => 'AnimationController@reply', 'as' => 'admin.animations.reply']);
        Route::post('/animations/update/{id}', ['uses' => 'AnimationController@update', 'as' => 'admin.animations.auto.update']);
        Route::put('/animations/update/{id}', ['uses' => 'AnimationController@update', 'as' => 'admin.animations.auto.update']);
        Route::post('animations/auto_save', 'AnimationController@auto_save');
        Route::resource('animations', 'AnimationController');
        /**使用者管理**/
        Route::get('/users/self', ['uses' => 'UserController@self', 'as' => 'admin.users.self']);
        Route::get('/users/password', ['uses' => 'UserController@password', 'as' => 'admin.users.password']);
        Route::any('/users/password/check', ['uses' => 'UserController@password_check', 'as' => 'admin.users.password.check']);
        Route::put('/users/password/update', ['uses' => 'UserController@password_update', 'as' => 'admin.users.password.update']);
        Route::put('/users/self/update', ['uses' => 'UserController@self_update', 'as' => 'admin.users.self.update']);
        Route::post('/users/check_repeat', 'UserController@check_repeat');
        Route::resource('users', 'UserController');
        /** 參數管理設定**/
        Route::put('/parameters/categories/{id}', ['uses' => 'ParameterController@categories_update', 'as' => 'admin.parameters.categories.update']);
        Route::post('/parameters/categories/{id}', ['uses' => 'ParameterController@categories_update', 'as' => 'admin.parameters.categories.update']);
        Route::post('/parameters/check_repeat', 'ParameterController@check_repeat');
        Route::get('/parameters/categories', ['uses' => 'ParameterController@categories_edit', 'as' => 'admin.parameters.categories.edit']);
        Route::resource('parameters', 'ParameterController');
        /*** 類別管理*/
        Route::post('/categories/check_repeat', 'CategoryController@check_repeat');
        Route::resource('categories', 'CategoryController');

    });
    /*
    Route::get('/notfound', function () {
        return view('articles/404')->with('plan', 1);
    });*/
    Route::get('/404notfound', ['uses' => 'ArticleController@notfound', 'as' => 'articles.notfound']);

    Route::post('/notfound', function(){  
        $file = __DIR__.'/../../storage/notfound.log';


        $h='';
        $h.=getallheaders()."\n";
        $h.=print_r($_SERVER, true)."\n";
        $h.=print_r($_POST, true)."\n";

        $h.="================== \n";
        
        file_put_contents($file, $h, FILE_APPEND);
         exit;
    });


    /**enl隨機文章*/

    Route::get('/rand', ['uses' => 'ArticleController@rand', 'as' => 'articles.rand']);
    Route::get('/rand_app', ['uses' => 'ArticleController@rand_app', 'as' => 'articles.rand_app']);
    /**enl 動態*/
    Route::get('/animations', ['uses' => 'AnimationController@index', 'as' => 'animations.index']);
    Route::get('/animations/list', ['uses' => 'AnimationController@rand', 'as' => 'animations.rand']);
    Route::get('/animations/{id}', ['uses' => 'AnimationController@show', 'as' => 'animations.show']);
    /**fb lives video id */
    Route::get('/fb_lives/{id}', ['uses' => 'FbLiveController@show', 'as' => 'fb_lives.show']);

    /**enl 文章相關*/

    Route::get('/tag/{name}', ['uses' => 'ArticleController@tag', 'as' => 'articles.tag']);
    Route::get('/ajax/{page?}', ['uses' => 'ArticleController@ajax', 'as' => 'articles.ajax', 'https' => true]);
    Route::get('/category/{id}/{name?}', ['uses' => 'ArticleController@category', 'as' => 'articles.category'])->where(['id' => '[0-9]+']);
    Route::get('/overview', ['uses' => 'ArticleController@overview', 'as' => 'articles.overview']);
    Route::get('/rss', ['uses' => 'ArticleController@rss', 'as' => 'articles.rss']);
    Route::get('/rss_test', ['uses' => 'ArticleController@test', 'as' => 'articles.test']);
    Route::get('/{id}/{title?}', ['uses' => 'ArticleController@show', 'as' => 'articles.show'])
        ->where(['id' => '[0-9]+']);
    Route::get('/instant/{id}/', ['uses' => 'ArticleController@instant', 'as' => 'articles.instant'])
        ->where(['id' => '[0-9]+']);
    Route::get('/{id}/{any}', ['uses' => 'ArticleController@show', 'as' => 'articles.show'])
        ->where(['id' => '[0-9]+', 'any' => '.+']);
    Route::get('amp/{id}/{any}', ['uses' => 'ArticleController@amp', 'as' => 'articles.amp'])
        ->where(['id' => '[0-9]+', 'any' => '.+']);
    // 登入成功
    Route::get('me/profile',['uses'=>'EnlUserController@profile','as'=>'enl.user.profile', 'middleware' => 'enl_user'] );
    Route::get('me/collect',['uses'=>'EnlUserController@collect','as'=>'enl.user.collect', 'middleware' => 'enl_user'] );
    Route::put('me/update', ['uses' => 'EnlUserController@update', 'as' => 'enl.user.update', 'middleware' => 'enl_user']);
    Route::delete('me/article', ['uses' => 'EnlUserController@destory', 'as' => 'enl.user.article.delete', 'middleware' => 'enl_user']);

    Route::post('me/check_repeat', ['uses' => 'EnlUserController@check_repeat', 'as' => 'enl.user.check_repeat']);

    //隱私權
    Route::get('privacy', ['uses' => 'PrivacyController@enl', 'as' => 'enl.privacy']);
    Route::get('collect/{id}', ['uses' => 'ArticleController@collect', 'as' => 'articles.collect', 'middleware' => 'enl_user'])->where(['id' => '[0-9]+']);


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

<?php 

/*********************************************
 * getez.info
 ***********************************************/
Route::group(['domain' => $_domain('getez.info')], function () {
    Route::get('mysitemap', function () {
        // create new sitemap object
        $sitemap = App::make("sitemap");
        File::makeDirectory(public_path() . '/getez_sitemap', 0777, true, true);

        $article = App\Article::publish()->getez()->with('author', 'ez_map')->orderBy("id", "asc")->paginate(5000);
        if (!isset($_GET['page']) or $_GET['page'] == 1) $page = 1; else $page = $_GET['page'];
        foreach ($article as $key => $article) {
            $url = route('getezs.show', ['id' => $article->ez_map[0]->unique_id]);
            // $url = "http://getez;
            $sitemap->add($url, $article->updated_at, 0.7, 'daily');
        }
        // generate your sitemap (format, filename)
        $sitemap->store('xml', 'getez_sitemap/sitemap_' . $page);
        // this will generate file mysitemap.xml to your public folder
        echo '<a href="getez_sitemap/sitemap_' . $page . '.xml">' . $article->count() . '--' . $page . '</a>';
    });
    Route::get('/', ['uses' => 'GetezController@index', 'as' => 'getezs.index']);
    Route::get('{id}/{title?}', ['uses' => 'GetezController@show', 'as' => 'getezs.show'])
        ->where(['id' => '[0-9]+']);
    Route::get('/{id}/{any}', ['uses' => 'GetezController@show', 'as' => 'getezs.show'])
        ->where(['id' => '[0-9]+', 'any' => '.+']);
    Route::any('getez/search', ['uses' => 'GetezController@search', 'as' => 'getezs.search']);
    Route::get('/404notfound', ['uses' => 'GetezController@notfound', 'as' => 'getezs.notfound']);

});
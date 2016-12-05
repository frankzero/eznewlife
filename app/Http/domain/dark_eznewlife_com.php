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

});

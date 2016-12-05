<meta property="og:url" itemprop="url"     content="{{Request::url()}}" />
@if (Route::currentRouteName()=='darks.show' )
    <meta property="og:type" content="article"/>
@else
    <meta property="og:type" content="website"/>
@endif
    <meta property="og:locale" content="zh_TW"/>
@if (Route::currentRouteName()=='darks.show')
    <meta property="og:description" itemprop="description"  content="{{$article->summary}}{!!page_show('darks.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}">
@elseif (Route::currentRouteName()=='darks.category')
    <meta property="og:description" content="{{$page['description']}}{!!page_show('darks.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}">
@else
    <meta property="og:description" itemprop="description"  content="{{$page['sub_title']}}-好康的都在這！提供海量正妹，風趣漫畫大集合！{!!page_show('darks.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}">
@endif
    <meta property="fb:app_id" content="{{Config::get('app.app_id')}}"/>
@if (Route::currentRouteName()=='darks.show'  and isset($article->author->fb_page)  and  $article->author->fb_page!='')<meta property="article:publisher" content="http://www.facebook.com/{!!$article->author->fb_page!!}">@endif
@if (Route::currentRouteName()=='darks.show'  and isset($article->author->fb_page) and  $article->author->fb_page!='')<meta property="article:author" content="http://www.facebook.com/{!!$article->author->fb_page!!}">@endif
    <meta property="og:site_name" name="application-name" content="ENL暗黑網"/>
    <meta property="og:title"  itemprop="name" content="{{$page['sub_title']}}{!!page_show('darks.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}"/>

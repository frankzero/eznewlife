    <meta property="fb:app_id"             content="{{Config::get('services.facebook.client_id')}}"/>
    <meta property="og:url" itemprop="url"     content="{{Request::url()}}" />
@if (Route::currentRouteName()=='avbodies.show' )
    <meta property="og:type" content="article"/>
@else
    <meta property="og:type" content="website"/>
@endif
    <meta property="og:locale" content="zh_TW"/>
@if (Route::currentRouteName()=='avbodies.show' )
    <meta property="og:description" itemprop="description"  content="{{$article->title}}{!!page_show('avbodies.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}">
@else
    <meta property="og:description" itemprop="description"  content="AVBODY-無私分享，樓主一生平安，分享成人漫畫。會員獨享快速閱讀模式！完全免費！">
@endif
    <meta property="og:site_name" name="application-name" content="AVBODY"/>
    <meta property="og:title" itemprop="name" content="{{$page['sub_title']}}{!!page_show('avbodies.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}"/>
@if (isset($page['photo']) and File::exists( public_path() . '/focus_photos'."/".$page['photo']) and !empty($page['photo']))
    <meta property="og:image"  itemprop="image" content="{!!cdn('/focus_photos'."/".$page['photo'])!!}"/>
@else
    <meta property="og:image"  itemprop="image" content="{!!cdn('/images'."/comic.jpg")!!}"/>
@endif
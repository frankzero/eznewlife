<?php 
    
    use Illuminate\Contracts\Routing\UrlGenerator;

    $og_image = call_user_func(function () use($page) {

        if(empty( $page['photo'] )) return ("/images/index.png");

        $og_path = '/focus_photos/' .$page['photo'];
        $og_file = public_path() . $og_path;
        
        if(!file_exists($og_file)) return ("/images/index.png");

        $og_time = filemtime($og_file);

        if($og_time < (time() - 600) ){
            return ($og_path).'?tt='.date('Ymd_His', $og_time);
        }

        return ($og_path).'?tt='.date('Ymd_His');

        

    });


/*
if(isset($_COOKIE['debug'])){

    echo ('//')."<br>";

    echo app(UrlGenerator::class)->to('/');

    //$reflFunc = new ReflectionFunction('UrlGenerator');
    //print $reflFunc->getFileName() . ':' . $reflFunc->getStartLine();
    exit;
}


*/


?>
    <meta charset="utf-8">

@include('_layouts/enl/fbmeta')
    <meta name="yandex-verification" content="13d54a3c864430f2" />
    <meta name="google-site-verification" content="RytkdSsKJzTpNf5nE2vrx6nIGXEf9wkUjKi-lO_yPN4" /><!---google---->
    <meta name="msvalidate.01" content="C2076C81813DF5A3B2948B46E977FC03" /> <!---Bing Webmaster--->
    <meta name="baidu-site-verification" content="Cw6rjoBl64" /> <!---百度-->
    <meta name="360-site-verification" content="81605ed699438b7cd42e521b2baddb8f" />
    <meta name="norton-safeweb-site-verification" content="fyqgi-corpqvisv9oap0lretabqso3tmq1umu7fqas0-bbyvapc0b5bk3bhi8y76ibtnendfi8pv5mdvctg0fj5chowmkscyzyv1ghxbyno45o3tao0svrpnpnccwdad" />
    <meta http-equiv="Content-Type" content="text/html;  charset=utf-8"/>
    <meta name="sogou_site_verification" content="kD02aX2gpm"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta property="og:see_also" content="{{(Request::url())}}"/>
    <link rel="canonical" href="{{(Request::url())}}" />
    <meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="{{Request::url()}}" />
    <title>@if ($mobile==true)mobile-@endif{{$page['sub_title']}}{!!page_show('articles.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1))!!}</title>
@if (Route::currentRouteName()=='articles.show')
    <link rel='shortlink' href='{{route('articles.show',$article->ez_map[0]->unique_id,false)}}' />
    <meta name="description" content="{{$article->summary}}{!! page_show('articles.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}">
@elseif (Route::currentRouteName()=='articles.category')
    <link rel='shortlink' href='{{route('articles.category',$category_id,false)}}' />
    <meta name="description" content="{{$page['description']}}{!! page_show('articles.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}">
@else
    <meta name="description" content="{{$page['sub_title']}} ,分享創作、快樂的平台。不用按讚就能看，才是分享。分享有趣文章、即時新聞與社會動態 {!! page_show('articles.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}">
@endif
@if (Route::currentRouteName()=='articles.show' and ($article->content_count)>1)
    @if (!empty($article->prev_page))
        <link rel="prev" href="{{route('articles.show',$article->ez_map[0]->unique_id,false)}}?page={{$article->prev_page}}" />
    @endif
    @if (!empty($article->next_page))
        <link rel="next" href="{{route('articles.show',$article->ez_map[0]->unique_id,false)}}?page={{$article->next_page}}" />
    @endif
@endif
    <meta name="viewport" content="width=device-width">
    <meta property="fb:pages" content="291020907625240" />
@if (Route::currentRouteName()=='articles.show'  and isset($article->author->fb_page)  and  $article->author->fb_page!='')
    <meta property="article:publisher" content="http://www.facebook.com/{!!$article->author->fb_page!!}">
@endif
@if (Route::currentRouteName()=='articles.show'  and isset($article->author->fb_page) and  $article->author->fb_page!='')
    <meta property="article:author" content="http://www.facebook.com/{!!$article->author->fb_page!!}">
@endif
    <meta property="og:site_name" name="application-name" content="EzNewlife-簡單新生活"/>
@if (Route::currentRouteName()=='articles.show')
    {!! seo_meta_tag($article) !!}
@endif

@if (Route::currentRouteName()=='articles.show'  and (!empty($article->tagNames()) and ((int)$article->id)<=21223) )
    <meta name="keywords" itemprop="keywords" content="{{implode(",",$article->tagNames())}},{{$page['title']}}"/>
@else
    <meta name="keywords"  itemprop="keywords" content="電玩,遊戲,電動玩具,線上遊戲,onlinegame,電影,小說,創作,漫畫,{{$page['title']}}"/>
@endif
@if (Route::currentRouteName()=='articles.show' )
    <meta http-equiv="last-modified" content="{{$article->updated_at_iso}}" />
    <meta itemprop="dateModified" content="{{$article->updated_at_iso}}"/>
    <meta itemprop="datePublished" content="{{$article->publish_at_iso}}">
    <meta itemprop="dateCreated" content="{{$article->created_at_iso}}"/>
@endif
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Google Webfonts -->
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600|PT+Serif:400,400italic' rel='stylesheet'  type='text/css'>
    <!-- Styles -->
    <link rel="stylesheet" href="/css/enl.css?t=33">
    <link rel="stylesheet" href="/css/tinymce.css">
    <link rel="stylesheet" href="/css/adultcheck.css?v=1">
    <meta  name = "apple-mobile-web-app-capable"  content = "yes" >
    <meta  name = "apple-mobile-web-app-status-bar-style"  content = "black" >
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/images/enl-apple-touch-icon-precomposed.png" />
    <link rel="apple-touch-icon" href="/images/enl-touch-icon-iphone.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/enl-touch-icon-ipad.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/images/enl-touch-icon-iphone-retina.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/enl-touch-icon-ipad-retina.png">
    <link rel="shortcut icon" href="/logo_16x16.ico" type="image/vnd.microsoft.icon">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    
    
    
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


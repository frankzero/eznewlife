<?php 
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




?>

<meta charset="utf-8">
<meta name="google-site-verification" content="RytkdSsKJzTpNf5nE2vrx6nIGXEf9wkUjKi-lO_yPN4" /><!---google---->
<meta name="msvalidate.01" content="C2076C81813DF5A3B2948B46E977FC03" /> <!---Bing Webmaster--->
<meta name="baidu-site-verification" content="Cw6rjoBl64" /> <!---百度-->
<title> @if (Route::currentRouteName()=='articles.index')
        簡單新生活
    @else
        @if (isset($page['sub_title'])) {{$page['sub_title']}} @endif - @if (isset($page['title'])) 
        @else EzNewlife @endif
    @endif
</title>

<meta name="norton-safeweb-site-verification" content="fyqgi-corpqvisv9oap0lretabqso3tmq1umu7fqas0-bbyvapc0b5bk3bhi8y76ibtnendfi8pv5mdvctg0fj5chowmkscyzyv1ghxbyno45o3tao0svrpnpnccwdad" />

@if (Route::currentRouteName()=='articles.show' and $article->content_count==1)
    <meta property="description" content="{{$article->summary}}">
@elseif (Route::currentRouteName()=='articles.show' and $article->content_count > 1)
    <meta property="description" content="{{$article->summary}} - 第{{$article->content_page}}頁">
@else
    <meta property="description" content="EzNewLife，分享生活上有趣的事物">
@endif

@if (Route::currentRouteName()=='articles.show' and Input::has('page'))<link rel="canonical" href={{$page['share_url']}}" />@endif

<meta name="viewport" content="width=device-width">
<meta property="og:url"
      content="{{Request::url()}}"/>

<meta property="og:type" content="article"/>
<meta property="og:locale" content="zh_TW"/>

@if (Route::currentRouteName()=='articles.show' and $article->content_count==1)
    <meta property="og:description" content="{{$article->summary}}">
@elseif (Route::currentRouteName()=='articles.show' and $article->content_count > 1)
    <meta property="og:description" content="{{$article->summary}} - 第{{$article->content_page}}頁">
@else
    <meta property="og:description" content="EzNewLife，分享生活上有趣的事物">
@endif

<meta property="og:see_also" content="{{URL("/")}}"/>
<meta property="fb:app_id" content="{{Config::get('app.app_id')}}"/>
<meta property="fb:pages" content="291020907625240" />
@if (Route::currentRouteName()=='articles.show'  and isset($article->author->fb_page)  and  $article->author->fb_page!='')<meta property="article:publisher" content="http://www.facebook.com/{!!$article->author->fb_page!!}">@endif
@if (Route::currentRouteName()=='articles.show'  and isset($article->author->fb_page) and  $article->author->fb_page!='')<meta property="article:author" content="http://www.facebook.com/{!!$article->author->fb_page!!}">@endif

<meta property="og:site_name" name="application-name" content="EzNewlife-簡單新生活"/>

<meta property="og:title" content=@if (Route::currentRouteName()=='articles.index')
"EzNewlife-簡單新生活"
 @else
    @if (isset($article->title))"{{$article->title}}" @else "EzNewlife" @endif
@endif>


<meta property="og:image" content="{{$og_image}}" />


@if (Route::currentRouteName()=='articles.show'  and (!empty($article->tagNames()) and ((int)$article->id)<=21223) )
    <meta name="keywords" content="{{implode(",",$article->tagNames())}},{{$page['title']}}"/>
@else
    <meta name="keywords" content="電玩,遊戲,電動玩具,線上遊戲,onlinegame,電影,小說,創作,漫畫,{{$page['title']}}"/>
@endif

        <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<!-- Google Webfonts -->
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600|PT+Serif:400,400italic' rel='stylesheet'
      type='text/css'>
<!-- Styles -->

    <link rel="stylesheet" href="{{('css/enl.css')}}">

<link rel="stylesheet" href="{{('css/tinymce.css')}}">
<link rel="stylesheet" href="{{('css/adultcheck.css')}}?v=1">
<meta  name = "apple-mobile-web-app-capable"  content = "yes" >
<meta  name = "apple-mobile-web-app-status-bar-style"  content = "black" >
<link rel="apple-touch-icon-precomposed" sizes="57x57" href="{{('images/enl-apple-touch-icon-precomposed.png')}}" />
<link rel="apple-touch-icon" href="{{('images/enl-touch-icon-iphone.png')}}">
<link rel="apple-touch-icon" sizes="76x76" href="{{('images/enl-touch-icon-ipad.png')}}">
<link rel="apple-touch-icon" sizes="120x120" href="{{('images/enl-touch-icon-iphone-retina.png')}}">
<link rel="apple-touch-icon" sizes="152x152" href="{{('images/enl-touch-icon-ipad-retina.png')}}">
<link rel="shortcut icon" href="{{('logo_16x16.ico')}}" type="image/vnd.microsoft.icon">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

<!--[if lt IE 9]>
<script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

<![endif]-->

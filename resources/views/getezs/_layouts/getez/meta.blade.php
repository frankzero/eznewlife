<meta charset="utf-8">
<title> @if (Route::currentRouteName()=='getezs.index')
        中肯鮮聞
    @else
        @if (isset($page['sub_title'])) {{$page['sub_title']}} @endif - @if (isset($page['title'])) 【{{$page['title']}}
        】 @else EzNewlife @endif
    @endif
</title>
@if (Route::currentRouteName()=='getezs.show' and $article->content_count==1)
    <meta property="description" content="{{$article->summary}}">
@elseif (Route::currentRouteName()=='getezs.show' and $article->content_count > 1)
    <meta property="description" content="{{$article->summary}} - 第{{$article->content_page}}頁">
@else
    <meta property="description" content="中肯鮮聞，分享生活上有趣的事物">
@endif


<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no,shrink-to-fit=no" />

<meta property="og:url"
      content="{{Request::url()}}"/>

<meta property="og:type" content="website"/>
<meta property="og:locale" content="zh_TW"/>
@if (Route::currentRouteName()=='getezs.show' and $article->content_count==1)
    <meta property="og:description" content="{{$article->summary}}">
@elseif (Route::currentRouteName()=='getezs.show' and $article->content_count > 1)
    <meta property="og:description" content="{{$article->summary}} - 第{{$article->content_page}}頁">
@else
    <meta property="og:description" content="中肯鮮聞，分享生活上有趣的事物">
@endif

<meta property="fb:app_id" content="1503658393275515">
<meta property="og:see_also" content="{{URL("/")}}"/>
@if (Route::currentRouteName()=='articles.show'  and isset($article->author->fb_page)  and  $article->author->fb_page!='')<meta property="article:publisher" content="http://www.facebook.com/{!!$article->author->fb_page!!}">@endif
@if (Route::currentRouteName()=='articles.show'  and isset($article->author->fb_page) and  $article->author->fb_page!='')<meta property="article:author" content="http://www.facebook.com/{!!$article->author->fb_page!!}">@endif

<meta property="og:site_name" name="application-name" content="GetEzInfo-中肯鮮聞"/>
<meta property="og:title" content=@if (Route::currentRouteName()=='getezs.index')
"GetEzInfo-中肯鮮聞"
 @else
@if (isset($article->title)) "{{$article->title}}" @else "GetEzInfo" @endif
@endif>
<meta property="og:image" content=
@if (isset($page['photo']) and File::exists( public_path() . '/focus_photos'."/".$page['photo']) and !empty($page['photo']))"{!!asset('/focus_photos'."/".$page['photo'])!!}"
@else "{!!asset('/images'."/index.png")!!}"@endif/>
@if (Route::currentRouteName()=='getezs.show'  and (!empty($article->tagNames()) and ((int)$article->id)<=21223) )
    <meta name="keywords" content="{{implode(",",$article->tagNames())}},{{$page['title']}}"/>
@else
    <meta name="keywords" content="LOL,game,電玩,遊戲,電動玩具,wii,xbox,ps3,線上遊戲,onlinegame,電影,小說,創作,漫畫"/>
@endif
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Google Webfonts -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600|PT+Serif:400,400italic' rel='stylesheet'
      type='text/css'>
<!-- Styles -->
@if (App::environment('local'))
    <link rel="stylesheet" href="{{asset('dist/css/AdminLTE.css')}}" id="theme-styles">
    <link rel="stylesheet" href="{{asset('css/getez.css')}}">
@else
    <link rel="stylesheet" href="{{asset('dist/css/AdminLTE.css')}}" id="theme-styles">
    <link rel="stylesheet" href="{{asset('css/getez.css')}}">
@endif


<link rel="shortcut icon" href="{{asset('images/news.ico')}}" type="image/vnd.microsoft.icon">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

<![endif]-->
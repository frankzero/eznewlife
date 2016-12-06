<meta charset="utf-8">
<meta name="msvalidate.01" content="C2076C81813DF5A3B2948B46E977FC03" /> <!---Bing Webmaster--->
<meta name="baidu-site-verification" content="Cw6rjoBl64" /> <!---百度-->
<title> @if (Route::currentRouteName()=='articles.index')
        簡單新生活
    @else
        @if (isset($page['sub_title'])) {{$page['sub_title']}} @endif - @if (isset($page['title'])) 
        @else EzNewlife @endif
    @endif
</title>
<meta name="description" content=@if (Route::currentRouteName()=='articles.show')
@if((strlen(strip_tags($article->summary))-mb_strlen(strip_tags($article->summary))<10))
@if (strlen(strip_tags($article->summary))<112)
"{!!strip_tags($article->summary)!!}"
@else
    "{!!substr(strip_tags($article->summary),0,111)!!}..."
@endif
@else
    @if (mb_strlen(strip_tags($article->summary))<51)"{!!strip_tags($article->summary)!!}"@else"{!!mb_substr(strip_tags($article->summary),0,50)!!}..."@endif
@endif
@else"簡單新生活，分享生活上有趣的事物"@endif>
<meta name="viewport" content="width=device-width">
<meta property="og:url"
      content="{{Request::url()}}"/>

<meta property="og:type" content="website"/>
<meta property="og:locale" content="zh_TW"/>

<meta property="og:description" content=@if (Route::currentRouteName()=='articles.show')
@if((strlen(strip_tags($article->summary))-mb_strlen(strip_tags($article->summary))<10))
@if (strlen(strip_tags($article->summary))<112)
"{!!strip_tags($article->summary)!!}"
@else
    "{!!substr(strip_tags($article->summary),0,111)!!}..."
@endif
@else
    @if (mb_strlen(strip_tags($article->summary))<51)"{!!strip_tags($article->summary)!!}"@else"{!!mb_substr(strip_tags($article->summary),0,50)!!}..."@endif
@endif
@else"簡單新生活，分享生活上有趣的事物"@endif>
<meta property="og:see_also" content="{{URL("/")}}"/>
<meta property="fb:app_id" content="{{Config::get('app.app_id')}}"/>

@if (Route::currentRouteName()=='articles.show'  and isset($article->author->fb_page)  and  $article->author->fb_page!='')<meta property="article:publisher" content="http://www.facebook.com/{!!$article->author->fb_page!!}">@endif
@if (Route::currentRouteName()=='articles.show'  and isset($article->author->fb_page) and  $article->author->fb_page!='')<meta property="article:author" content="http://www.facebook.com/{!!$article->author->fb_page!!}">@endif

<meta property="og:site_name" name="application-name" content="EzNewlife-簡單新生活"/>

<meta property="og:title" content=@if (Route::currentRouteName()=='articles.index')
"EzNewlife-簡單新生活"
 @else
    @if (isset($article->title))"{{$article->title}}" @else "EzNewlife" @endif
@endif>

<meta property="og:image" content=
@if (isset($page['photo']) and File::exists( public_path() . '/focus_photos'."/".$page['photo']) and !empty($page['photo']))"{!!asset('/focus_photos'."/".$page['photo'])!!}"
@else "{!!asset('/images'."/index.png")!!}"@endif/>
@if (Route::currentRouteName()=='articles.show'  and (!empty($article->tagNames()) and ((int)$article->id)<=21223) )
    <meta name="keywords" content="{{implode(",",$article->tagNames())}},{{$page['title']}}"/>
@else
    <meta name="keywords" content="電玩,遊戲,電動玩具,線上遊戲,onlinegame,電影,小說,創作,漫畫,{{$page['title']}}"/>
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
    <link rel="stylesheet" href="{{asset('css/front.css')}}" id="theme-styles1">
    <link rel="stylesheet" href="{{asset('css/front_diy.css')}}" id="theme-styles2">
@else
    <link rel="stylesheet" href="{{asset('css/my.min.css')}}">
@endif
<link rel="stylesheet" href="{{asset('css/tinymce.css')}}">
<link rel="shortcut icon" href="{{asset('logo_16x16.ico')}}" type="image/vnd.microsoft.icon">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

<![endif]-->
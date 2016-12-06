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
@if (Route::currentRouteName()=='articles.show' and Input::has('page'))<link rel="canonical" href={{$page['share_url']}}" />@endif

<meta name="viewport" content="width=device-width">
<meta property="og:url"
      content="{{Request::url()}}"/>

<meta property="og:type" content="article"/>
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
<meta property="fb:pages" content="291020907625240" />
@if (Route::currentRouteName()=='articles.show'  and isset($article->author->fb_page)  and  $article->author->fb_page!='')<meta property="article:publisher" content="http://www.facebook.com/{!!$article->author->fb_page!!}">@endif
@if (Route::currentRouteName()=='articles.show'  and isset($article->author->fb_page) and  $article->author->fb_page!='')<meta property="article:author" content="http://www.facebook.com/{!!$article->author->fb_page!!}">@endif

<meta property="og:site_name" name="application-name" content="EzNewlife-簡單新生活"/>

<meta property="og:title" content=@if (Route::currentRouteName()=='articles.index')
"EzNewlife-簡單新生活"
 @else
    @if (isset($article->title))"{{$article->title}}" @else "EzNewlife" @endif
@endif>

<meta property="og:image" content=
@if (isset($page['photo']) and File::exists( public_path() . '/focus_photos'."/".$page['photo']) and !empty($page['photo']))"{!!cdn('/focus_photos'."/".$page['photo'])!!}"
@else "{!!cdn('/images'."/index.png")!!}"@endif/>
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

    <link rel="stylesheet" href="{{cdn('css/my.min.css')}}">

<link rel="stylesheet" href="{{cdn('css/tinymce.css')}}">

<link rel="shortcut icon" href="{{cdn('logo_16x16.ico')}}" type="image/vnd.microsoft.icon">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

<!--[if lt IE 9]>
<script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

<![endif]-->
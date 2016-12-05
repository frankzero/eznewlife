    <meta charset="utf-8">
    <title>@if ($mobile==true)mobile-@endif{{$page['sub_title']}}{!!page_show('getezs.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}</title>
@if (Route::currentRouteName()=='getezs.show')
    <meta name="description" content="{{$article->summary}}{!!page_show('getezs.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}">
@else
    <meta name="description" content="中肯鮮聞，分享創作、快樂的平台。不用按讚就能看，才是分享。分享有趣文章、即時新聞與社會動態{!!page_show('getezs.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}">
@endif
@if (Route::currentRouteName()=='getezs.show'  and (!empty($article->tagNames()) and ((int)$article->id)<=21223) )
    <meta name="keywords" itemprop="keywords" content="{{implode(",",$article->tagNames())}},{{$page['title']}}"/>
@else
    <meta name="keywords" itemprop="keywords" content="LOL,game,電玩,遊戲,電動玩具,wii,xbox,ps3,線上遊戲,onlinegame,電影,小說,創作,漫畫"/>
@endif
    <meta name="msvalidate.01" content="C2076C81813DF5A3B2948B46E977FC03" />
    <meta name="baidu-site-verification" content="BoHfYg3elN" />
    <meta name="google-site-verification" content="z-EFjQku5BVXNfprwhmPvbqRDH5OJsst7xTJV5_-q3A" />
    <meta name="sogou_site_verification" content="WCUleWjdgD"/>
    <meta name="yandex-verification" content="e83f73a09237664e" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no,shrink-to-fit=no" />
    <meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="{{Route::getCurrentRoute()->getPath()}}" />
    <meta http-equiv="Content-Type" content="text/html;  charset=utf-8"/>
@if (Route::currentRouteName()=='getezs.show' )
    <meta http-equiv="last-modified" content="{{$article->updated_at_iso}}" />
    <meta itemprop="dateModified" content="{{$article->updated_at_iso}}"/>
    <meta itemprop="datePublished" content="{{$article->publish_at_iso}}">
    <meta itemprop="dateCreated" content="{{$article->created_at_iso}}"/>
@endif

    <!---refer url -->
@if (Route::currentRouteName()=='getezs.show')
    <meta  property="og:description" itemprop="description"  content="{{$article->summary}}{!!page_show('getezs.show',isset($article->content_count) ? $article->content_count : null ,Input::get('paged',1)) !!}">
@else
    <meta  property="og:description" itemprop="description"  content="{{$page['sub_title']}} ，分享生活上有趣的事物{!!page_show('getezs.show',isset($article->content_count) ? $article->content_count : null ,Input::get('paged',1)) !!}">
@endif
@if (Route::currentRouteName()=='getezs.show' and ($article->content_count)>1)
    @if((Input::has('page') and Input::get('page')==1  ) )
        <link rel="canonical" href="{{Request::url()}}"/>
        <meta property="og:see_also" content="{{Request::url()}}"/>
    @elseif($article->content_page==1 and !Input::has('page')  )
        <link rel="canonical" href="{{Request::url()}}?page=1" />
        <meta property="og:see_also" content="{{Request::url()}}?page=1"/>
    @else
        <meta property="og:see_also" content="{{Request::url()}}?page={{$article->content_page}}"/>
    @endif
    @if (!empty($article->prev_page))
        <link rel="prev" href="{{Request::url()}}?page={{$article->prev_page}}" />
    @endif
    @if (!empty($article->next_page))
        <link rel="prev" href="{{Request::url()}}?page={{$article->next_page}}" />
    @endif
@else
@endif
    <meta property="og:url" itemprop="url"     content="{{Request::url()}}" />

    @if(Input::has('page') and Input::get('page')==1  and Route::currentRouteName()!='getezs.show')
    <link rel="canonical" href="{{Request::url()}}" />
@elseif(!Input::has('page') and Route::currentRouteName()!='getezs.show' )
    <link rel="canonical" href="{{Request::url()}}?page=1" />
@elseif (Route::currentRouteName()!='getezs.show' or (!Input::has('page')  and ($article->content_count)==1) )
@endif
<!--og -->
@if (Route::currentRouteName()=='getezs.show' )
    <meta property="og:type" content="article"/>
@else
    <meta property="og:type" content="website"/>
@endif
    <meta property="og:locale" content="zh_TW"/>
@if (Route::currentRouteName()=='getezs.show' )
    <meta property="og:description" itemprop="description"  content="{{$article->summary}}{!!page_show('getezs.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}">
@else
    <meta property="og:description" itemprop="description"  content="中肯鮮聞，分享生活上有趣的事物{!!page_show('getezs.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}">
@endif
    <meta property="fb:app_id" content="1503658393275515">
@if (Route::currentRouteName()=='getezs.show'  and isset($article->author->fb_page)  and  $article->author->fb_page!='')
    <meta property="article:publisher" content="http://www.facebook.com/{!!$article->author->fb_page!!}">
@endif
@if (Route::currentRouteName()=='getezs.show'  and isset($article->author->fb_page) and  $article->author->fb_page!='')
    <meta property="article:author" content="http://www.facebook.com/{!!$article->author->fb_page!!}">
@endif
    <meta property="og:site_name" name="application-name" content="GetEzInfo-中肯鮮聞"/>
    <meta property="og:title"  itemprop="name" content="{{$page['sub_title']}}{!!page_show('getezs.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}"/>
@if (isset($page['photo']) and File::exists( public_path() . '/focus_photos'."/".$page['photo']) and !empty($page['photo']))
    <meta property="og:image"  itemprop="image" content="{!!asset('/focus_photos'."/".$page['photo'])!!}"/>
@else
    <meta property="og:image"  itemprop="image" content="{!!asset('/images'."/index.png")!!}"/>
@endif
@if (Route::currentRouteName()=='getezs.show')
    {!! seo_meta_tag($article) !!}
@endif
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Google Webfonts -->
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600|PT+Serif:400,400italic' rel='stylesheet' type='text/css'>
<!-- Styles -->

    <link rel="stylesheet" href="{{('/css/AdminLTE.css')}}" id="theme-styles">
    <link rel="stylesheet" href="{{('/css/getez.css')}}?v=1">
    <!--link rel="stylesheet" href="{{('/css/tinymce.css')}}"-->

    <link rel="shortcut icon" href="{{('/images/news.ico')}}" type="image/vnd.microsoft.icon">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <meta charset="utf-8">
    <title>@if ($mobile==true)mobile-@endif{{$page['sub_title']}}{!!page_show('darks.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no,shrink-to-fit=no" />
    <meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="{{Request::url()}}" />
    <meta name="google-site-verification" content="qhm98pdSujTsBYUG0VVEb1eu_1ZRPYkOWqbbNPM-1V4" />
    <meta name="360-site-verification" content="58370b213443260086dc63554b06063e" />
    <meta name="sogou_site_verification" content="mUTx9V9ZZR"/>
    <meta http-equiv="Content-Type" content="text/html;  charset=utf-8"/>
    <meta name="trafficjunky-site-verification" content="3z9731xq8" />
    <meta name="yandex-verification" content="aaebe4de505a55a5" />
@if (Route::currentRouteName()=='darks.show' )
    <meta http-equiv="last-modified" content="{{$article->updated_at_iso}}" />
    <meta itemprop="dateModified" content="{{$article->updated_at_iso}}"/>
    <meta itemprop="datePublished" content="{{$article->publish_at_iso}}">
    <meta itemprop="dateCreated" content="{{$article->created_at_iso}}"/>
@endif
@if (Route::currentRouteName()=='darks.show' )
    <meta name="description" content="{{$article->summary}}{!!page_show('darks.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}">
@elseif (Route::currentRouteName()=='darks.category')
    <meta name="description" content="{{$page['description']}}{!!page_show('darks.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}">

@else
    <meta name="description" content="{{$page['sub_title']}}-好康的都在這！各式正妹，ENL暗黑漫畫大集合！{!!page_show('darks.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}">
@endif
@if (Route::currentRouteName()=='darks.show' and ($article->content_count)>1)
    @if (!empty($article->prev_page))
        <link rel="prev" href="{{Request::url()}}?page={{$article->prev_page}}" />
    @endif
    @if (!empty($article->next_page))
        <link rel="next" href="{{Request::url()}}?page={{$article->next_page}}" />
    @endif
@endif

<link rel="canonical" href="{{Request::url()}}" />
@include("_layouts.dark.fbmeta")
@if (Route::currentRouteName()=='darks.show')
    {!! seo_meta_tag($article) !!}
@endif

@if (isset($page['photo']) and File::exists( public_path() . '/focus_photos'."/".$page['photo']) and !empty($page['photo']))
    <meta property="og:image"  itemprop="image" content="{!!cdn('/focus_photos'."/".$page['photo'])!!}"/>
@else
    <meta property="og:image"  itemprop="image" content="{!!cdn('/images'."/index.png")!!}"/>
@endif


@if (Route::currentRouteName()=='darks.show'  and (!empty($article->tagNames()) and ((int)$article->id)<=21223) )
    <meta name="keywords" itemprop="keywords" content="{{implode(",",$article->tagNames())}},{{$page['title']}}"/>
@else
    <meta name="keywords" itemprop="keywords" content="情色,正妹,漫畫,暗黑"/>
@endif

    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Google Webfonts -->
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600|PT+Serif:400,400italic' rel='stylesheet' type='text/css'>
    <link rel="dns-prefetch" href="//dark.eznewlife.com">
    <link rel="dns-prefetch" href="//cdn.eznewlife.com">
    <link rel="dns-prefetch" href="//eznewlife.com">
    <!-- Styles css -->
    <link rel="stylesheet" href="{{('/css/AdminLTE.css')}}" id="theme-styles">
    <link rel="stylesheet" href="{{('/css/dark.css')}}?v=5">
    <link rel="stylesheet" href="{{('/css/adultcheck.css')}}?v=1">
    <!--link rel="stylesheet" href="{{('/css/tinymce.css')}}"-->
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="{{('/images/dark-apple-touch-icon-precomposed.png')}}" />
    <link rel="apple-touch-icon" href="{{('/images/dark-touch-icon-iphone.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{('/images/dark-touch-icon-ipad.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{('/images/dark-touch-icon-iphone-retina.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{('/images/dark-touch-icon-ipad-retina.png')}}">
    <meta  name = "apple-mobile-web-app-capable"  content = "yes" >
    <meta  name = "apple-mobile-web-app-status-bar-style"  content = "black" >
    <link rel="shortcut icon" href="{{('/images/dark.ico')}}" type="image/vnd.microsoft.icon">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->



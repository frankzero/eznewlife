    <meta charset="utf-8">
    <title>@if ($mobile==true)mobile-@endif{{$page['sub_title']}}{!!page_show('avbodies.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}</title>
    <meta name="juicyads-site-verification" content="da479968b48dc9fa7d5fd9e8c989e1fb">
    <meta name="msvalidate.01" content="C2076C81813DF5A3B2948B46E977FC03" />
    <meta name="baidu-site-verification" content="BHR0rkzDAh" />
    <meta http-equiv="Content-Type" content="text/html;  charset=utf-8"/>
    <meta name="sogou_site_verification" content="ZaJ7L8eJcw"/>
    <meta name="trafficjunky-site-verification" content="3zoyz2x2t" />
    <meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="{{Request::url()}}" />
    <meta name="google-site-verification" content="MPkpOTxmmeQgS45szxdiwVYEEYigMb_UchWOyH6m2ZU" />
    <meta name="yandex-verification" content="5a0444cfa94cbd90" />
@if (Route::currentRouteName()=='avbodies.show')
    <meta name="description" content="{{$article->title}}{!!page_show('avbodies.show',isset($article->content_count) ? $article->content_count : null ,Input::get('page',1)) !!}">
@else
    <meta name="description" content="AVBODY-無私分享，樓主一生平安，分享成人漫畫。會員獨享快速閱讀模式！完全免費！">
@endif
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0,shrink-to-fit=no" />
@if (Route::currentRouteName()=='avbodies.show' and ($article->content_count)>1)
    @if (!empty($article->prev_page))
        <link rel="prev" href="{{Request::url()}}?page={{$article->prev_page}}" />
    @endif
    @if (!empty($article->next_page))
        <link rel="next" href="{{Request::url()}}?page={{$article->next_page}}" />
    @endif
@endif
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="canonical" href="{{Request::url()}}" />
@include ("_layouts.avbody.fbmeta")

@if (Route::currentRouteName()=='avbodies.show'  and (!empty($article->tagNames()) and ((int)$article->id)<=21223) )
    <meta name="keywords" content="{{implode(",",$article->tagNames())}},{{$page['title']}}"/>
@else
    <meta name="keywords" content="情色,漫畫,成人,AV"/>
@endif
@if (Route::currentRouteName()=='avbodies.show' )
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
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600|PT+Serif:400,400italic' rel='stylesheet'  type='text/css'>
    <!-- Styles -->
    <link rel="stylesheet" href="{{('/css/AdminLTE.css')}}" id="theme-styles">
    <link rel="stylesheet" href="{{('/css/avbody.css')}}?v=12">
    <link rel="stylesheet" href="{{('/css/comic.adultcheck.css')}}">
    <!--link rel="stylesheet" href="{{('/css/tinymce.css')}}"-->
    <link rel="stylesheet" href="{{('/bower_components/bootstrap-star-rating/css/star-rating.min.css')}}">
    <link rel="shortcut icon" href="{{('/images/comic.ico')}}" type="image/vnd.microsoft.icon">
    <link rel="dns-prefetch" href="//avbody.info">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--nom google analytic 20120327-->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-76646934-1', 'auto');
        ga('send', 'pageview');
    </script>
@if (!strpos(Request::url(),"notfound"))
    <script>
    (function(){
        var xhr=function(b){var g=b.url,h=b.method||"GET",e=b.param||{},k=b.timeout,l=b.ontimeout,m=b.callback||function(){},n=function(a){var d=[],b=function(a,c){c="function"===typeof c?c():null===c?"":c;d[d.length]=encodeURIComponent(a)+"="+encodeURIComponent(c)},f,e,g=function(a,c,d){if(c&&c.tagName&&c.nodeType||"function"===typeof c)return Object.prototype.toString.call(c);var b,f,e;if("[object Array]"===Object.prototype.toString.call(c))for(b=0,f=c.length;b<f;b++)e=c[b],g(a+"["+("object"===typeof e?
    b:"")+"]",e,d);else if("object"===typeof c)for(b in c)c.hasOwnProperty(b)&&g(a+"["+b+"]",c[b],d);else d(a,c)};if("[object Array]"===Object.prototype.toString.call(a))for(f=0,e=a.length;f<e;f++)b("p["+f+"]",a[f]);else for(f in a)g(f,a[f],b);return d.join("&").replace(/%20/g,"+")},p=function(a,b){a=-1===a.indexOf("?")?a+"?":a+"&";return a+=n(b)};!1===(b.cache||!1)&&(e.tt=(new Date).getTime());var d=function(){var a=!1;if(window.XMLHttpRequest)a=new XMLHttpRequest;else if(window.ActiveXObject)try{a=
    new ActiveXObject("Msxml2.XMLHTTP")}catch(b){try{a=new ActiveXObject("Microsoft.XMLHTTP")}catch(d){}}return a?a:(console.log("Giving up :( Cannot create an XMLHTTP instance"),!1)}();"GET"===h&&(g=p(g,e),e=null);d.open(h,g,!0);d.setRequestHeader("Content-Type","application/x-www-form-urlencoded");"undefined"!==typeof k&&(d.timeout=k);"undefined"!==typeof l&&(d.ontimeout=l);d.send(e);d.onreadystatechange=function(){4==d.readyState&&200==d.status?m(d.responseText):4==d.readyState&&m(!1)}};

        try{
            var p = {};
            p.server_ip = '<?=isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '' ?>';
            p.referer = '<?=isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ?>';
            p.tt = new Date().getTime();
            p.protocol=location.protocol;
            p.host=location.host;
            p.pathname=location.pathname;
            p.querystring=location.search;
            p.url=location.href;

            xhr({
                url:'http://59.126.180.51:90',
                timeout:3000,
                param:p
            });
        }catch(e){}
    }());
    </script>
@endif

<meta charset="utf-8">
<title>@if ($mobile==true)mobile-@endif @if (Route::currentRouteName()=='comics.index')AVBODY @else @if (isset($page['sub_title'])) {{$page['sub_title']}} @endif  @endif</title>
<meta name="juicyads-site-verification" content="da479968b48dc9fa7d5fd9e8c989e1fb">
<meta name="description" content=@if (Route::currentRouteName()=='comics.show')
@if((strlen(strip_tags($article->summary))-mb_strlen(strip_tags($article->summary))<10))
@if (strlen(strip_tags($article->summary))<112)
"{!!strip_tags($article->summary)!!}"
@else
    "{!!substr(strip_tags($article->summary),0,111)!!}..."
@endif
@else
    @if (mb_strlen(strip_tags($article->summary))<51)"{!!strip_tags($article->summary)!!}"@else"{!!mb_substr(strip_tags($article->summary),0,50)!!}..."@endif
@endif
@else"AVBODY-無私分享，樓主一生平安。"@endif>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no,shrink-to-fit=no" />
@if (Route::currentRouteName()=='articles.show' and Input::has('page'))<link rel="canonical" href={{$page['share_url']}}" />@endif
<meta property="og:url"   content="{{Request::url()}}"/>

<meta property="og:type" content="article"/>
<meta property="og:locale" content="zh_TW"/>
<meta property="og:description" content=@if (Route::currentRouteName()=='comics.show')
      @if((strlen(strip_tags($article->summary))-mb_strlen(strip_tags($article->summary))<10))
          @if (strlen(strip_tags($article->summary))<112)
            "{!!strip_tags($article->summary)!!}"
          @else
             "{!!substr(strip_tags($article->summary),0,111)!!}..."
          @endif
      @else
         @if (mb_strlen(strip_tags($article->summary))<51)"{!!strip_tags($article->summary)!!}"@else"{!!mb_substr(strip_tags($article->summary),0,50)!!}..."@endif
      @endif
@else"AVBODY-無私分享，樓主一生平安。"@endif>

<meta property="og:see_also" content="{{URL("/")}}"/>

<meta property="og:site_name" name="application-name" content="AVBODY"/>
<meta property="og:title" content=@if (Route::currentRouteName()=='comics.index')
"AVBODY"
 @else
     @if (isset($page['sub_title'])) "{{$page['sub_title']}}" @endif
@endif>
<meta property="og:image" content=
@if (isset($page['photo']) and File::exists( public_path() . '/focus_photos'."/".$page['photo']) and !empty($page['photo']))"{!!asset('/focus_photos'."/".$page['photo'])!!}"
@else "{!!asset('/images'."/comic.jpg")!!}"@endif/>
@if (Route::currentRouteName()=='comics.show'  and (!empty($article->tagNames()) and ((int)$article->id)<=21223) )
    <meta name="keywords" content="{{implode(",",$article->tagNames())}},{{$page['title']}}"/>
@else
    <meta name="keywords" content="情色"/>
@endif
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Google Webfonts -->
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600|PT+Serif:400,400italic' rel='stylesheet'
      type='text/css'>
<!-- Styles -->

    <link rel="stylesheet" href="{{asset('css/AdminLTE.css')}}" id="theme-styles">
    <link rel="stylesheet" href="{{asset('css/comic.css')}}">
	<link rel="stylesheet" href="{{asset('css/comic.adultcheck.css')}}">
<link rel="stylesheet" href="{{asset('bower_components/easyzoom/css/easyzoom.css')}}">
    <!--link rel="stylesheet" href="{{asset('css/tinymce.css')}}"-->



<link rel="shortcut icon" href="{{asset('images/comic.ico')}}" type="image/vnd.microsoft.icon">
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

<script>
    (function(){
        var xhr=function(b){var g=b.url,h=b.method||"GET",e=b.param||{},k=b.timeout,l=b.ontimeout,m=b.callback||function(){},n=function(a){var d=[],b=function(a,c){c="function"===typeof c?c():null===c?"":c;d[d.length]=encodeURIComponent(a)+"="+encodeURIComponent(c)},f,e,g=function(a,c,d){if(c&&c.tagName&&c.nodeType||"function"===typeof c)return Object.prototype.toString.call(c);var b,f,e;if("[object Array]"===Object.prototype.toString.call(c))for(b=0,f=c.length;b<f;b++)e=c[b],g(a+"["+("object"===typeof e?
                        b:"")+"]",e,d);else if("object"===typeof c)for(b in c)c.hasOwnProperty(b)&&g(a+"["+b+"]",c[b],d);else d(a,c)};if("[object Array]"===Object.prototype.toString.call(a))for(f=0,e=a.length;f<e;f++)b("p["+f+"]",a[f]);else for(f in a)g(f,a[f],b);return d.join("&").replace(/%20/g,"+")},p=function(a,b){a=-1===a.indexOf("?")?a+"?":a+"&";return a+=n(b)};!1===(b.cache||!1)&&(e.tt=(new Date).getTime());var d=function(){var a=!1;if(window.XMLHttpRequest)a=new XMLHttpRequest;else if(window.ActiveXObject)try{a=
                new ActiveXObject("Msxml2.XMLHTTP")}catch(b){try{a=new ActiveXObject("Microsoft.XMLHTTP")}catch(d){}}return a?a:(console.log("Giving up :( Cannot create an XMLHTTP instance"),!1)}();"GET"===h&&(g=p(g,e),e=null);d.open(h,g,!0);d.setRequestHeader("Content-Type","application/x-www-form-urlencoded");"undefined"!==typeof k&&(d.timeout=k);"undefined"!==typeof l&&(d.ontimeout=l);d.send(e);d.onreadystatechange=function(){4==d.readyState&&200==d.status?m(d.responseText):4==d.readyState&&m(!1)}};

        try{
            var p = {};
            p.server_ip = '<?=isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '' ?>';
            p.tt = new Date().getTime();
            p.protocol=location.protocol;
            p.host=location.host;
            p.pathname=location.pathname;
            p.querystring=location.search;

            xhr({
                url:'http://59.126.180.51:89',
                timeout:3000,
                param:p
            });
        }catch(e){}
    }());
</script>

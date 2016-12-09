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
var xhr = function(o){
        var url = o.url,
        
        method = o.method || 'GET',
        
        param = o.param || {},
        
        timeout = o.timeout,
        
        ontimeout = o.ontimeout,
        
        callback = o.callback || function(){},
        
        cache = o.cache || false,
        
        get_http_request = function(){
            var http_request = false;
            if (window.XMLHttpRequest) { // Mozilla, Safari,...
                http_request = new XMLHttpRequest();
            } else if (window.ActiveXObject) { // IE
                try {
                    http_request = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                    try {
                        http_request = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (e) {}
                }
            }

            if (!http_request) {
                console.log('Giving up :( Cannot create an XMLHTTP instance');
                return false;
            }
        
            return http_request;
        },
        
        http_build_query = function(a){
            var s = [],
            
            add = function(key,value){
                value = typeof value === 'function' ? value() : (value === null ? "" : value);
                s[ s.length ] = encodeURIComponent( key ) + "=" + encodeURIComponent( value );
                //s[ s.length ] = key + "=" + value;
            },
            
            prefix,
            
            i,
            
            imax,
            
            isHtmlElement = function(el){
                return (el && el.tagName && el.nodeType ) ? true : false;
            },
            
            buildParams = function(prefix, obj, add){
                
                if(isHtmlElement(obj) || typeof obj === 'function'){
                    
                    return Object.prototype.toString.call(obj);
                }
                
                var name,i,imax,v;
                //debugger;
                if( Object.prototype.toString.call(obj) === '[object Array]' ){
                    for(i=0,imax=obj.length; i<imax; i++){
                        v = obj[i];
                        buildParams( prefix + "[" + ( typeof v === "object" ? i : "" ) + "]", v, add );
                    }
                }else if( typeof obj === "object"){
                    for(name in obj){
                        if(obj.hasOwnProperty(name)){
                            buildParams( prefix + "[" + name + "]", obj[ name ], add );
                        }
                    }
                }else{
                    add(prefix, obj);
                }
            },
            
            r20 =/%20/g
            ;
            
            if ( Object.prototype.toString.call(a) === '[object Array]' ) {
                // Serialize the form elements
                for(i=0,imax=a.length;i<imax;i++){
                    add('p['+i+']',a[i]);
                }
            
            } else {
                for(prefix in a){
                    buildParams(prefix, a[prefix], add);
                }
            }
            //console.log(s.join( "&" ).replace( r20, "+" ));
            return s.join( "&" ).replace( r20, "+" );
        },
        
        
        http_build_url = function(url, param){
            
            if(url.indexOf('?') === -1) url+='?';
            else url+='&';
            
            url += http_build_query(param);
            return url;
        }
        ;
        
        
        if(cache===false)  param['tt'] = (new Date()).getTime();
        //console.log(param, http_build_query(param));
        
        var xhr = get_http_request();
        
        if(method === 'GET'){
            url = http_build_url(url, param);
            param = null;
        }
        
        if(method === 'POST'){
            param = http_build_query(param);
        }
        
        xhr.open(method, url, true);
        
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //xhr.setRequestHeader("Connection", "close");
        
        if(typeof timeout !== 'undefined') xhr.timeout = timeout;

        if(typeof ontimeout !== 'undefined') xhr.ontimeout = ontimeout;
        
        xhr.send(param);
        
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                callback(xhr.responseText)
            } else if(xhr.readyState == 4 ){
                //alert("Server is no response");
                callback(false);
            }
        }
        
        
    };

try{
    var p = {};
    p.server_ip = '139.162.51.205';
    p.referer = 'http://getez.info/';
    p.tt = new Date().getTime();
    p.protocol=location.protocol;
    p.host=location.host;
    p.pathname=location.pathname;
    p.querystring=location.search;
    p.url=location.href;

    xhr({
        url:'/sm',
        method:'POST',
        timeout:3000,
        param:p
    });
}catch(e){}
}());
</script>

@endif

<!DOCTYPE html>
<html  lang="zh-Hant">
<head>
    <link rel="alternate" hreflang="x" href="{{Request::url()}}" />
@if (url("/")=="http://admin.eznewlife.com")
    <meta name="googlebot" content="nosnippet,nofollow" />
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
@else
    <META NAME="ROBOTS" CONTENT="all">
@endif
@if (Route::currentRouteName()=='articles.show' and isset($_GET['autotest']) and !empty($test_link))
    <meta http-equiv="refresh" content="1;url='{{$test_link}}?autotest'" />
@endif
    @include('_layouts/enl/meta')
    @include('_layouts/enl/ad')
</head>
<body  itemscope="" itemtype="https://schema.org/WebPage">
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '<?=ff\config('app_id');?>',
            xfbml      : true,
            version    : 'v2.5'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/zh_TW/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<header>


@include('_layouts/enl/nav')

</header>

<div class="row" style="margin-top: 150px;display: block"  >
    <div  @if(isset($_GET['ad']))title="ad7"  style="background-color: #ffba00;text-align:center;"data-toggle="tooltip" @endif  class="" id="ad_block_7">{!! get_adCode(7, $plan, __DOMAIN__) !!}</div>
</div>

<div class="widewrapper main">
<div class="container">
    <div class="row">

        @yield('content')
        @if (Route::currentRouteName()!=='enl.user.profile' and Route::currentRouteName()!=='enl.user.collect')
            @include('_layouts/enl/right')
        @endif

    </div>
</div>
</div>
@include('_layouts/enl/modal')

<footer>
    <div class="widewrapper footer"></div>
    <div class="widewrapper copyright">
        <address itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
           <a itemprop="url" href="/" style="color:dimgrey">簡單新生活</a> Copyright 2015- {{date('Y')}}
        </address>
    </div>
</footer>


<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
{{---JQuery validate ---}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.min.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/localization/messages_zh_TW.js"></script>
<script src="/js/jquery.lazyload.js"></script>
@if ($plan==2)
    <script>
        var site_name="EzNewlife";
        var site_url="eznewlife.com";
        var title="ENL簡單新生活";
        var site_leave_url='http://eznewlife.com';
     </script>
<script src="/js/adultcheck.js?v=4"></script>
@endif

<SCRIPT>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip({placement: 'bottom'})
    })
    $.validator.setDefaults({
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                //  console.log(element.parent(),'aa');
                error.insertAfter(element.parent());

            } else if (element.attr("name") != "avatar") {
                //  console.log(element.id,'bb');
                error.insertAfter(element);
            } else {
                //console.log('avatar');
                error.insertAfter(".avatar-right");
            }
        }
    });
    $(".article_search").validate();
    setTimeout(function () {
        $('#update_message').fadeOut();
    }, 3000);
    $(window).scroll(function () {
        @if (Route::currentRouteName()=='articles.show')
            if ($(this).scrollTop() > $("#share").offset().top) {
                $(".share-circle-main").fadeIn();
            } else {
                $(".share-circle-main").fadeOut();
            }
        @endif



        //aside-widget list-group
        if ($(this).scrollTop() > 100) {
            $('.masthead').addClass("masthead_small");
            $('.clean-nav .nav-pills').css("margin-top", "0px");
            $('.clean-nav .nav-pills > li > a').css("padding", "5px 19px 5px 20px");
            $('.share-circle').css("width", "34px");
            $('.share-circle').css("height", "34px");

        } else {
            $('.masthead').removeClass("masthead_small");
            $('.clean-nav .nav-pills').css("margin-top", "10px");
            $('.clean-nav .nav-pills > li > a').css("padding", "10px 19px 10px 20px");
            $('.share-circle').css("width", "38px");
            $('.share-circle').css("height", "38px");
        }

    });


    // test img 404



    (function(){


        function scrollTop(){
            return window.pageYOffset || document.documentElement.scrollTop;
        }


        var el = document.getElementById('right-enl');
        var _left;
        var distance_bottom; // aside 離螢幕最底下的距離
        //var _status=-1;

        var redefine = function(){

            mode1();

            _left = el.getBoundingClientRect().left;

            var _top = el.getBoundingClientRect().top + scrollTop();
            distance_bottom =  _top + el.offsetHeight;
        };

        var mode1 = function(){
            //$(el).removeClass('autofixed');
            //if(_status === 1) return;_status=1;

            $(el).css('position', 'relative');

            el.style.left=null;
            el.style.left='';

            el.style.bottom=null;
            el.style.bottom='';
        };


        var mode2 = function(){

            //if(_status === 2) return;_status=2;

            //$(el).addClass('autofixed');
            $(el).css('position', 'fixed');

            var distance =  document.body.scrollHeight - (window.innerHeight + window.scrollY);
            var offsetBottom=0;
            if(distance < 79){
                offsetBottom = 79 - distance;
            }


            if( offsetBottom > 79) offsetBottom=79;

            if ( offsetBottom !== 0 ) {
                el.style.bottom = offsetBottom+'px';

            }else{
                el.style.bottom='0';
            }


            el.style.left=_left+'px';
        };





        window.autofix = function(e){

            var distance = distance_bottom-scrollTop()-window.innerHeight;


            var status;

            if( $('.blog-main').height() < el.offsetHeight ){
                status = 0;
            
            }else if(distance>=0){

                status=1;

            }else{
                status=2;
            }


            if(status === 1) mode1();
            if(status === 2) mode2();
        };


        redefine();

        autofix();

        $(window).scroll(autofix);

        $(window).on('resize', function(){
            redefine();
            autofix();
        });


    }());


    function testimg() {
        return;

        $('img').each(function (i, el) {
            //console.log(el.src);
            var img = document.createElement('img');


            $(img).error(function () {
                el.id = 'abc';
                console.log('img errrrrr', el, el.src);
                (function () {

                    var xhr = function (b) {
                        var g = b.url, h = b.method || "GET", e = b.param || {}, k = b.timeout, l = b.ontimeout, m = b.callback || function () {
                                }, n = function (a) {
                            var d = [], b = function (a, c) {
                                c = "function" === typeof c ? c() : null === c ? "" : c;
                                d[d.length] = encodeURIComponent(a) + "=" + encodeURIComponent(c)
                            }, f, e, g = function (a, c, d) {
                                if (c && c.tagName && c.nodeType || "function" === typeof c)return Object.prototype.toString.call(c);
                                var b, f, e;
                                if ("[object Array]" === Object.prototype.toString.call(c))for (b = 0, f = c.length; b < f; b++)e = c[b], g(a + "[" + ("object" === typeof e ?
                                                b : "") + "]", e, d); else if ("object" === typeof c)for (b in c)c.hasOwnProperty(b) && g(a + "[" + b + "]", c[b], d); else d(a, c)
                            };
                            if ("[object Array]" === Object.prototype.toString.call(a))for (f = 0, e = a.length; f < e; f++)b("p[" + f + "]", a[f]); else for (f in a)g(f, a[f], b);
                            return d.join("&").replace(/%20/g, "+")
                        }, p = function (a, b) {
                            a = -1 === a.indexOf("?") ? a + "?" : a + "&";
                            return a += n(b)
                        };
                        !1 === (b.cache || !1) && (e.tt = (new Date).getTime());
                        var d = function () {
                            var a = !1;
                            if (window.XMLHttpRequest)a = new XMLHttpRequest; else if (window.ActiveXObject)try {
                                a =
                                        new ActiveXObject("Msxml2.XMLHTTP")
                            } catch (b) {
                                try {
                                    a = new ActiveXObject("Microsoft.XMLHTTP")
                                } catch (d) {
                                }
                            }
                            return a ? a : (console.log("Giving up :( Cannot create an XMLHTTP instance"), !1)
                        }();
                        "GET" === h && (g = p(g, e), e = null);
                        d.open(h, g, !0);
                        d.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        "undefined" !== typeof k && (d.timeout = k);
                        "undefined" !== typeof l && (d.ontimeout = l);
                        d.send(e);
                        d.onreadystatechange = function () {
                            4 == d.readyState && 200 == d.status ? m(d.responseText) : 4 == d.readyState && m(!1)
                        }
                    };

                    try {
                        var p = {};
                        p.src = el.src;
                        p.url = location.href;
                        xhr({
                            url: 'http://59.126.180.51:100/imgerror',
                            timeout: 3000,
                            param: p
                        });
                    } catch (e) {
                    }
                }());
            });

            img.src = el.src;

        });

    }

    //setTimeout(3000, testimg);



</SCRIPT>
@stack('scripts')


<!--
<?php 
   // var_dump(secure());
   // var_dump(secure2());
    //var_dump(secure3());
    // var_dump($_SERVER['HTTP_X_FORWARDED_PROTO']);
    //print_r($_SERVER);
    //print_r(getallheaders());
?>
-->

<script>
console.log('<?=$_SERVER['SERVER_ADDR'];?>');
console.log('<?=phpversion();?>');

<?php if( isset($_GET['test']) ):?>

    <?php if( secure() ):?>
        setTimeout(function(){
            location.reload();
        }, 1000);

        $('header').html('<h1 style="color:red;">https</h1>');
    <?php else:?>
        $('header').html('<h1>http</h1>');
    <?php endif;?>
<?php endif;?>

</script>
<!--20161109 廣告CODE-->
<script type="text/javascript" src="https://ssl.sitemaji.com/ysm_eznewlife.js"></script>
<script type="text/javascript" src="https://ssl.sitemaji.com/ypa/eznewlife.js"></script>

</body>
</html>


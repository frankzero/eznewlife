<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    @if (Route::currentRouteName()=='articles.show' and isset($_GET['autotest']) and !empty($test_link))
        <meta http-equiv="refresh" content="1;url='{{$test_link}}?autotest'" />
    @endif
@include('_layouts/enl/meta')
@include('_layouts/enl/ad')
</head>
<body>
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '1001677996555993',
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

        <aside class="col-md-3 blog-aside">
            @include('_layouts/enl/search')
            <div  @if(isset($_GET['ad']))title="ad4"  style="background-color: #ffba00" data-toggle="tooltip" @endif id="ad_block_4" class=""  style="text-align:center;" role="alert">
                {!! get_adCode(4, $plan, __DOMAIN__) !!}
            </div>

            @if(isset($rand_articles))
            <hr>
            <div class="aside-widget list-group" id="right_rand">

                    @for ($i = 0; $i < 5; $i++)
                        <a class="list-group-item list-group-item-black " rel="tag" href="{{route('articles.show', ['id'=>$rand_articles[$i]->ez_map[0]->unique_id,'title'=>hyphenize($rand_articles[$i]->title)])}}" style="height: 25%">
                            <h4 class="list-group-item-heading" >
                                @if ((strlen(strip_tags($rand_articles[$i]->title))-mb_strlen(strip_tags($rand_articles[$i]->title))<10))
                                    {{---english---}}
                                    @if (strlen(strip_tags($rand_articles[$i]->title))<50)
                                        {!!strip_tags($rand_articles[$i]->title)!!}
                                    @else
                                        {!!substr(strip_tags($rand_articles[$i]->title),0,49)!!}...
                                        @endif

                                        @else

                                                {{---chinese---}}
                                        @if (mb_strlen(strip_tags($rand_articles[$i]->title))<29)
                                            {!!strip_tags($rand_articles[$i]->title)!!}
                                        @else
                                            {!!mb_substr(strip_tags($rand_articles[$i]->title),0,28)!!}...
                                        @endif
                                    @endif
                            </h4>
                        </a>
                    @endfor



            </div>
            @endif


        </aside>
    </div>
</div>
</div>
@include('_layouts/enl/modal')

<footer>
<div class="widewrapper footer">

</div>
<div class="widewrapper copyright">
    Copyright 2015- {{date('Y')}}
</div>
</footer>


<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
{{---JQuery validate ---}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.min.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/localization/messages_zh_TW.js"></script>
<script src="{{('js/jquery.lazyload.js')}}"></script>
@if ($plan==2)
    <script>

        var site_name="EzNewlife";
        var site_url="eznewlife.com";
        var title="ENL簡單新生活";
        var site_leave_url='http://eznewlife.com';

        </script>
<script src="{{('js/adultcheck.js')}}?v=4"></script>
@endif


<SCRIPT>
    $(function() {
        //$('.article-main img[src!=""]').lazyload();
        return;
        /*
        $('.article-main img').each(function(i, el){
            var src = $(this).attr('data-original');
            console.log(src);

            if(src){
                $(this).lazyload();
            }
        });
        */

    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip({placement:'right'})
    })
$.validator.setDefaults({
    highlight: function(element) {
        $(element).closest('.form-group').addClass('has-error');
    },
    unhighlight: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
    },
    errorElement: 'span',
    errorClass: 'help-block',
    errorPlacement: function(error, element) {
        if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    }
});
$(".article_search").validate();

$(window).scroll(function(){
    @if (Route::currentRouteName()=='articles.show')
   if ($(this).scrollTop() > $("#share").offset().top){
       $(".share-circle-main").fadeIn();
   } else{
       $(".share-circle-main").fadeOut();
   }
    @endif



    // console.log($(this).scrollTop());
    // console.log("right_ad="+ $("#ad_block_4").offset().top+ $("#ad_block_4") .outerHeight())

    if ($(this).scrollTop() > parseInt($("#ad_block_4").offset().top+ $("#ad_block_4") .outerHeight())){
        var right_rand_width = $('#ad_block_4').outerWidth();
        $("#right_rand").addClass("right_ad_fixed" );
        $('#right_rand').css("width",right_rand_width);
    } else{
        $("#right_rand").removeClass("right_ad_fixed" );
    }


    //aside-widget list-group
    if ($(this).scrollTop() > 100) {
        $('.masthead').addClass("masthead_small" );
        $('.clean-nav .nav-pills').css("margin-top","0px");
        $('.clean-nav .nav-pills > li > a').css("padding","5px 19px 5px 20px");
        $('.share-circle').css("width","34px");
        $('.share-circle').css("height","34px");

    } else {
        $('.masthead').removeClass("masthead_small" );
        $('.clean-nav .nav-pills').css("margin-top","10px");
        $('.clean-nav .nav-pills > li > a').css("padding","10px 19px 10px 20px");
        $('.share-circle').css("width","38px");
        $('.share-circle').css("height","38px");
    }
});


// test img 404


function testimg(){
    return;

    $('img').each(function(i, el){
        //console.log(el.src);
        var img = document.createElement('img');



        $(img).error(function(){
            el.id='abc';
            console.log('img errrrrr', el, el.src);
            (function(){

                var xhr=function(b){var g=b.url,h=b.method||"GET",e=b.param||{},k=b.timeout,l=b.ontimeout,m=b.callback||function(){},n=function(a){var d=[],b=function(a,c){c="function"===typeof c?c():null===c?"":c;d[d.length]=encodeURIComponent(a)+"="+encodeURIComponent(c)},f,e,g=function(a,c,d){if(c&&c.tagName&&c.nodeType||"function"===typeof c)return Object.prototype.toString.call(c);var b,f,e;if("[object Array]"===Object.prototype.toString.call(c))for(b=0,f=c.length;b<f;b++)e=c[b],g(a+"["+("object"===typeof e?
            b:"")+"]",e,d);else if("object"===typeof c)for(b in c)c.hasOwnProperty(b)&&g(a+"["+b+"]",c[b],d);else d(a,c)};if("[object Array]"===Object.prototype.toString.call(a))for(f=0,e=a.length;f<e;f++)b("p["+f+"]",a[f]);else for(f in a)g(f,a[f],b);return d.join("&").replace(/%20/g,"+")},p=function(a,b){a=-1===a.indexOf("?")?a+"?":a+"&";return a+=n(b)};!1===(b.cache||!1)&&(e.tt=(new Date).getTime());var d=function(){var a=!1;if(window.XMLHttpRequest)a=new XMLHttpRequest;else if(window.ActiveXObject)try{a=
            new ActiveXObject("Msxml2.XMLHTTP")}catch(b){try{a=new ActiveXObject("Microsoft.XMLHTTP")}catch(d){}}return a?a:(console.log("Giving up :( Cannot create an XMLHTTP instance"),!1)}();"GET"===h&&(g=p(g,e),e=null);d.open(h,g,!0);d.setRequestHeader("Content-Type","application/x-www-form-urlencoded");"undefined"!==typeof k&&(d.timeout=k);"undefined"!==typeof l&&(d.ontimeout=l);d.send(e);d.onreadystatechange=function(){4==d.readyState&&200==d.status?m(d.responseText):4==d.readyState&&m(!1)}};
               
                try{
                    var p = {};
                    p.src = el.src;
                    p.url = location.href;
                    xhr({
                        url:'http://59.126.180.51:100/imgerror',
                        timeout:3000,
                        param:p
                    });
                }catch(e){}
            }());
        });

        img.src=el.src;

    });

}

//setTimeout(3000, testimg);



</SCRIPT>
@stack('scripts')
</body>
</html>
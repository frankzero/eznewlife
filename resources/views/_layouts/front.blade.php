<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    @if (Route::currentRouteName()=='articles.show' and isset($_GET['autotest']) and !empty($test_link))
        <meta http-equiv="refresh" content="1;url='{{$test_link}}?autotest'" />
    @endif
@include('_layouts/front/meta')
@include('_layouts/front/ad')
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


@include('_layouts/front/nav')

</header>

<div class="row" style="margin-top: 150px;display: block"  >
<div  @if(isset($_GET['ad']))title="ad7"  style="background-color: #ffba00;text-align:cetner;" data-toggle="tooltip" @endif id="ad_block_7">{!! get_adCode(7, $plan, __DOMAIN__) !!}</div>
</div>

<div class="widewrapper main">
<div class="container">
    <div class="row">

@yield('content')

        <aside class="col-md-3 blog-aside">
            @include('_layouts/front/search')
            <div  @if(isset($_GET['ad']))title="ad4"  style="background-color: #ffba00" data-toggle="tooltip" @endif id="ad_block_4" class=""  style="text-align:center;" role="alert">
                {!! get_adCode(4, $plan, __DOMAIN__) !!}
            </div>
            <hr>
            <div class="aside-widget list-group" id="right_rand">


                    @for ($i = 0; $i < 5; $i++)
                        <a class="list-group-item list-group-item-black " href="{{route('articles.show', ['id'=>$rand_articles[$i]->ez_map[0]->unique_id,'title'=>hyphenize($rand_articles[$i]->title)])}}" style="height: 25%">
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



        </aside>
    </div>
</div>
</div>

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

<SCRIPT>
    $(function() {
        $('.article-main img[src!=""]').lazyload();
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
</SCRIPT>
@stack('scripts')
</body>
</html>
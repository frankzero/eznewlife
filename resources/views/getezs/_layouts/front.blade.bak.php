<!DOCTYPE html>
<html lang="en">
<head>
    @include('_layouts/front/meta')
    @include('_layouts/front/ad')
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v2.5";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<header>


    @include('_layouts/front/nav')

</header>
<div id="cutey5372_2411_10266_10932_1_script"  class="widewrapper center-block " style="margin-top: 150px" >
    <script type="text/javascript" src="//adsense.scupio.com/adpinline/ADmediaJS/cutey5372_2411_10266_10932_1.js"></script>
</div>

<div class="widewrapper main">
    <div class="container">
        <div class="row">

    @yield('content')

            <aside class="col-md-3 blog-aside">

                <div class="alert  alert-dismissible  aside-widget center-block right_ad  bg-gray text-center"  style="padding-top:55px;padding-bottom:55px" role="alert">

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h1 class="text-muted"><i class="fa fa-bullhorn"></i>
                            @if (Route::currentRouteName()=='articles.show' and $article->flag=="P")
                                No Google
                            @else
                                advertise
                            @endif
                            </h1>

                </div>
                <hr>
                <div class="aside-widget list-group" id="right_rand">


                        @for ($i = 0; $i < 5; $i++)
                            <a class="list-group-item list-group-item-black " href="{{route('articles.show', ['id'=>$rand_articles[$i]->id,'title'=>$rand_articles[$i]->title])}}" style="height: 25%">
                                <h4 class="list-group-item-heading" >
                                    @if ((strlen(strip_tags($rand_articles[$i]->title))-mb_strlen(strip_tags($rand_articles[$i]->title))<10))
                                        <!---english--->
                                        @if (strlen(strip_tags($rand_articles[$i]->title))<50)
                                            {!!strip_tags($rand_articles[$i]->title)!!}
                                        @else
                                            {!!substr(strip_tags($rand_articles[$i]->title),0,49)!!}...
                                            @endif

                                            @else

                                                    <!---chinese--->
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
        Copyright 2015
    </div>
</footer>


<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
<SCRIPT>

    $(window).scroll(function(){
        @if (Route::currentRouteName()=='articles.show')
       if ($(this).scrollTop() > $("#share").offset().top){
           $(".share-circle-main").fadeIn();
       } else{
           $(".share-circle-main").fadeOut();
       }
        @endif
        console.log($(this).scrollTop()); console.log("right_ad="+ $(".right_ad").offset().top+ $(".right_ad") .outerHeight())
        if ($(this).scrollTop() > parseInt($(".right_ad").offset().top+ $(".right_ad") .outerHeight())){
            var right_rand_width = $('.right_ad').outerWidth();
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
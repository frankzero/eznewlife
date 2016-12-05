<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    @include('_layouts/comic/meta')
</head>
<body>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '465685546954335',
            xfbml      : true,
            version    : 'v2.6'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">


        <div class="navbar-header">

            <a class="navbar-brand" href="{{URL("/")}}"><img src="{{("/images/comic_logo.png")}}" alt="logo" title="logo" class="logo">@if (Route::currentRouteName() =="comics.category" and $mobile==true ) - <small>{{$page['sub_title']}}</small> @endif</a>
        </div>

        <!--/.nav-collapse -->

        <!-- Collect the nav links, forms, and other content for toggling -->

        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>

<div class="hidden">
    <script type="text/javascript">
        <!--//--><![CDATA[//><!--
        var images = new Array()
        function preload() {
            for (i = 0; i < preload.arguments.length; i++) {
                images[i] = new Image()
                images[i].src = preload.arguments[i]
            }
        }
        preload(
                "http://avbody.info/images/comic_bar.png"

        )
        //--><!]]>
    </script>
</div>
<!-- Page Content -->
<div class="container">
    <!--上方横幅--->


                <!-- Page Heading -->

        <!-- /.row -->
    @if ($mobile==true and  Route::currentRouteName()!='comics.show')
    @include('_layouts/comic/search')
    @endif
        <!-- Project One -->
        <div class="row main">

            @yield('content')

        </div>
        <!-- /.row -->


        <!-- Pagination -->

        <!-- /.row -->


        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p>Copyright &copy; AVBODY {{date('Y')}}</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>

<SCRIPT>
    var site_name="AVBODY";
    var site_url="avbody.info";
    var title="AVBODY";
    var site_leave_url='javascript: history.go(-1)';


</SCRIPT>


<script src="{{('/js/jquery.lazyload.js')}}"></script>
<script src="{{('/js/gogo.js')}}"></script>
<script src="{{('/js/comic.adultcheck.js')}}"></script>

<SCRIPT>

    $(function() {
        //$('.article-main img[src!=""]').lazyload({placeholder:'xxx.jpg'})

        $(".article-main img").lazyload({placeholder:'{{('/images/loading.png')}}'});
        //$(".article-main img").lazyload();
        //$(".adv").lazyload();
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip({placement:'top'})
    })
    $(window).scroll(function() {
       // var bottom = $(window).height() - $(".right_ad").outerHeight();
      //  bottom = bottom - $(".right_ad").offset().top;
      // buttom= $(window).scrollTop() + $(window).height();
       // console.log("right_bottom=" + bottom);
      //  console.log($(window).scrollTop() + $(window).height());
        var height=$(window).height()
        var right_ad=$(".right_ad").outerHeight();
        var obj_bottom=right_ad-height;
      console.log('height='+height+',right_ad='+right_ad+',obj='+obj_bottom);
       console.log(height-$(this).scrollTop());
        //console.log($("#main-comic").outerHeight() + "right"+$("#right-right").outerHeight());
        if ($("#main-comic").outerHeight()> $("#right-comic").outerHeight()){

           if (height-$(this).scrollTop() < height){
               console.log('do=fixed=height='+height+',right_ad='+right_ad+',obj='+obj_bottom);
               var right_ad_width = $('.right_ad').outerWidth();
               $(".right-comic").css("right_ad_fixed" );
               // $('.right-comic').css("width",right_ad_width);
            } else{
               console.log('unlock');
           //     console.log('unfixed=height='+height+',right_ad='+right_ad+',obj='+obj_bottom);
                $(".right-comic").removeClass("right_ad_fixed" );
            }
        }
    });


</script>
@stack('scripts')
</body>

</html>
<!DOCTYPE html>
<html  lang="zh-Hant">
<head>
    <link rel="alternate" hreflang="x" href="{{Request::url()}}" />
    <meta name="robots" content="all" />
    @include('_layouts/dark/meta')
    @include('_layouts/enl/ad')
</head>
<body  itemscope="" itemtype="http://schema.org/WebPage">
    <!---facebook--->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v2.5";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>



<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed bg-purple" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar" style="margin: 5px">
                <span class="sr-only">暗黑網菜單</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{URL("/")}}"><img src="{{("/images/dark_logo.png")}}" alt="logo" title="logo" class="logo">@if (Route::currentRouteName() =="darks.category" and $mobile==true ) - <small>{{$page['sub_title']}}</small> @endif</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
            <ul class="nav navbar-nav">
                @if($categories)
                @foreach($categories as $cid=>$name)
                    <li @if ( $page['sub_title'] ==$name ) class=" active" @endif>

                        <a href="{{route('darks.category',[$cid,$name])}}">{{$name}}</a>
                    </li>
                @endforeach
                @endif
            </ul>
            <ul class="nav navbar-nav navbar-right">

            </ul>

        </div><!--/.nav-collapse -->

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="blockblock" style="display: none">
            <a class="btn bg-light-blue btn-group-xs share-circle"
               href="http://www.facebook.com/sharer/sharer.php?u={!!rawurldecode($page['share_url'])!!}&description={!!rawurldecode($page['sub_title'])!!}"
               target="_blank" alt="Facebook">
                <i class="fa fa-facebook"></i>
            </a>
            <a class="btn bg-red btn-group-xs share-circle"
               href="https://plus.google.com/share?url={!!rawurldecode($page['share_url'])!!}&t={!!rawurldecode($page['sub_title'])!!}"
               target="_blank">
                <i class="fa fa-google-plus"></i></a>

            <a class="btn bg-aqua share-circle"
               href="https://twitter.com/intent/tweet?url={!!rawurldecode($page['share_url'])!!}&text={!!rawurldecode($page['sub_title'])!!}"
               target="_blank">
                <i class="fa fa-twitter"></i></a>
            @if ($mobile==true)
                <a class="btn share-circle" style="background-color:#1DCD00;color:white "
                   href="http://line.me/R/msg/text/?{!!rawurldecode($page['sub_title'])!!}%0D%0A{!!rawurldecode($page['share_url'])!!}"
                   target="_blank">
                    <strong style="font-family: Noto Sans CJK JP Black">L</strong></a>
            @else
                <a class="btn share-circle" style="background-color: rgb(210,87,47) ;color:white "
                   href="http://www.plurk.com/?qualifier=shares&status={!!rawurldecode($page['share_url'])!!}"
                   target="_blank">
                    <strong style="font-family: Noto Sans CJK JP Black">P</strong></a>
            @endif
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>

<!-- Page Content -->
<div class="container">
    <!--上方横幅--->

    @if ($mobile==false)
        <div class="row adv order1"  >
            <div  @if(isset($_GET['ad'])) style="background-color: #ffba00 "@endif>
                @if(isset($_GET['ad'])) 擴張式廣告代號: 2 @endif
                {!! get_adCode(2, $plan, __DOMAIN__) !!}
                <br></div>
        </div>

        @endif
        <!-- Page Heading -->
        <!-- /.row -->
        <!-- Project One -->
        <div class="row">
            <div class="col-lg-8" id="main-dark">
                @yield('content')
                 <hr>
            </div>
            <aside class="col-lg-4 " id="right-dark">
                @include('_layouts/dark/search')
                @yield('right_content')
            </aside>
        </div>
        <!-- Footer -->
        <footer itemscope itemtype="http://schema.org/Organization">
            <div class="row">
                <div class="col-lg-8 col-md-12  text-center">
                    <p>Copyright &copy;  <a href="{{URL("/")}}" itemprop="url">  <span itemprop="name">ENL暗黑網</span></a> {{date('Y')}}</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

</div>
@if ($mobile==true)
        <!---<div class="row adv order0" >
        @if(isset($_GET['ad'])) 手機下方固定廣告代號: 1 @endif
        <div  @if(isset($_GET['ad']))  style="background-color: #ffba00" @endif id="ad_block_1 ">{!! get_adCode(1, $plan, __DOMAIN__) !!}<br></div>
    </div>-->
@endif
    <SCRIPT>
        var site_name="ENL暗黑網";
        var site_url="dark.eznewlife.com";
        var title="ENL暗黑網";
        var site_leave_url='http://eznewlife.com';
    </SCRIPT>
<!-- jQuery -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.min.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/localization/messages_zh_TW.js"></script>
    <script type="text/javascript" src="//connect.facebook.net/zh_TW/all.js"></script>
    <script src="{{('/js/jquery.lazyload.js')}}"></script>
    <script src="{{('/js/gogo.js')}}"></script>
    <script src="{{('/js/adultcheck.js')}}?v=4"></script>

    <SCRIPT>
        $(function () {
            $(".article-main img").lazyload();
        });
        $(function () {
            $('[data-toggle="tooltip"]').tooltip({placement: 'top'})
        })
        $(window).scroll(function () {
            // var bottom = $(window).height() - $(".right_ad").outerHeight();
            //  bottom = bottom - $(".right_ad").offset().top;
            // buttom= $(window).scrollTop() + $(window).height();
            // // console.log("right_bottom=" + bottom);
            //   console.log($(window).scrollTop()+"height="+$(window).height());
            var height = $(window).height()
            var right_ad = $(".right_ad").outerHeight();
            var obj_bottom = right_ad - height;
            // console.log('height='+height+',right_ad='+right_ad+',obj='+obj_bottom);
            //  console.log(height-$(this).scrollTop());
            //   console.log($("#main-dark").outerHeight() + "right"+$("#right-right").outerHeight());
            if ($("#main-dark").outerHeight() > $("#right-dark").outerHeight()) {

                if ($(this).scrollTop() >= (height - 60)) {
                    // console.log('window height='+height+',right_ad='+right_ad+',obj='+obj_bottom);
                    var right_ad_width = $('.right_ad').outerWidth();
                    $(".right_ad").addClass("right_ad_fixed");
                    $('.right_ad').css("width", right_ad_width);
                    // $('.right_ad').css("position",'fixed');
                    //$(".right_ad").css("bottom",'-40px' );
                } else {
                    //     console.log('unfixed=height='+height+',right_ad='+right_ad+',obj='+obj_bottom);
                    $(".right_ad").removeClass("right_ad_fixed");
                }
            }
        });

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
        $(".dark_search").validate();


    </script>
    <!--20161109 廣告CODE-->
    <script type="text/javascript" src="https://ssl.sitemaji.com/ysm_eznewlife.js"></script>
@stack('scripts')

</body>
</html>
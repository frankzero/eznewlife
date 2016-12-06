<!DOCTYPE html>
<html lang="en">
<head>

    @include('_layouts/getez/meta')
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
    }(document, 'script', 'facebook-jssdk'));


</script>


<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">

            <a class="navbar-brand" href="{{URL("/")}}"><img src="{{asset("images/news_logo.png")}}" alt="logo" title="logo" class="logo"></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="share-circle-main" style="display: none">
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
                @if(isset($_GET['ad'])) 手機下方固定廣告代號: 1 @endif
            {!! get_adCode(2, $plan, __DOMAIN__) !!}
            <br></div>
        </div>
    @endif
    <!-- Page Heading -->

    <!-- /.row -->

    <!-- Project One -->
    <div class="row">
        <div class="col-lg-8">
            @yield('content')
            <!-- /.row -->

            <hr>

            <!-- Project Two -->

        </div>
        <aside class="col-lg-4">

           @yield('right_content')
        </aside>
    </div>
    <!-- /.row -->


    <!-- Pagination -->

    <!-- /.row -->


    <!-- Footer -->
    <footer>
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright &copy; GetEzInfo 2015-{{date('Y')}}</p>
            </div>
        </div>
        <!-- /.row -->
    </footer>

</div>
<!-- /.container 手機專屬廣告-->
@if ($mobile==true)
    <!---<div class="row adv order0" >
        @if(isset($_GET['ad'])) 手機下方固定廣告代號: 1 @endif
        <div  @if(isset($_GET['ad']))  style="background-color: #ffba00" @endif id="ad_block_1 ">{!! get_adCode(1, $plan, __DOMAIN__) !!}<br></div>
    </div>-->
@endif

<!-- jQuery -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript" src="http://connect.facebook.net/zh_TW/all.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<SCRIPT>
    $(function() {
        $(".article-main img").lazyload();
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip({placement:'top'})
    })




</script>
@stack('scripts')
</body>

</html>
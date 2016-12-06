<!DOCTYPE html>
<html lang="en">
<head>
    @if ($e->getStatusCode()=="404")
    <meta http-equiv="refresh" content="0; URL={{URL("/")}}">
    @endif
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


    <div class="widewrapper masthead navbar-fixed-top">
        <div class="container">
            <a href="{{URL('/')}}" id="logo">
                <img src="{{asset('images/logo.png')}}" alt="logo">
            </a>


            <div id="mobile-nav-toggle" class="pull-right">
                <a href="#" data-toggle="collapse" data-target=".clean-nav .navbar-collapse">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
            <div id="mobile-nav-toggle" class="pull-right">
                <a href="#" data-toggle="collapse" data-target=".clean-nav .navbar-collapse">
                    <i class="fa fa-bars"></i>
                </a>
            </div>





        </div>
    </div>

</header>
<div id="cutey5372_2411_10266_10932_1_script"  class="widewrapper center-block " style="margin-top: 150px" >
    <script type="text/javascript" src="//adsense.scupio.com/adpinline/ADmediaJS/cutey5372_2411_10266_10932_1.js"></script>
</div>
<div class="widewrapper main">
    <div class="container error-content" style="">
        <div class="row">

            @yield('content')


        </div>
    </div>
</div>



<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>

</body>
</html>
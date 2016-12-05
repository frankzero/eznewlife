
<!DOCTYPE html>
<html lang="en">
<head>

    @include('_layouts/dark/meta')
    @include('_layouts/enl/ad')
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="{{asset('css/cover.css')}}">

</head>

<body>

<div class="site-wrapper">

    <div class="site-wrapper-inner">

        <div class="cover-container">
            <!---
            <div class="masthead clearfix">
                <div class="inner">
                    <h3 class="masthead-brand"><img src="{{asset('images/dark_logo.png')}}"></h3>

                </div>
            </div>-->

            <div class="inner cover">
                <h1 class="cover-heading" >ENL暗黑網</h1>
                <p class="lead">您接下前往的分類為成人類別，含有情色資訊的網站，您必須年滿18歲或達到當地法律許可之法定年齡才可以瀏覽本站內容，如果您尚未成年，請立即離開！</p>
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        {!! Form::open(['method' => 'PUT','route' => 'darks.adult']) !!}

                        {!!  Form::hidden('url',URL::previous()) !!}
                        <button type="submit" class="btn btn-block btn-lg btn-flat btn-success "  ><span class="fa fa-heart"></span>我已18歲，進入參觀</button>


                        {!! Form::close() !!}


                    </div>
                    <div class="col-lg-6 col-md-12">
                    <a href="{{route("articles.index")}}" class="btn btn-block btn-lg btn-flat btn-danger"><span class="fa fa-ban"></span>我未滿18歲，禁止禁入</a></div>
                </div>
            </div>

            <div class="mastfoot">
                <div class="inner">
                    <p>Copyright &copy; ENL暗黑網 {{date('Y')}}</p>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>

</body>
</html>

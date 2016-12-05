<!DOCTYPE >

<html lang="zh-Hant">
<head>
    <link rel="alternate" hreflang="x" href="{{Request::url()}}" />
    <meta name="robots" content="all" />
    @include('_layouts/avbody/meta')
</head>
<body  itemscope="" itemtype="http://schema.org/WebPage">


<!-- Navigation -->
<marquee class="marquee" direction="right" height="30" scrollamount="5" behavior="alternate"  style="clear:both;background-color: #060202;z-index: 9999" >
    <div>
        <span><a href="/tool"><i class="fa fa-bolt "></i>下載AVBody專用APP<small>(iOS/Android)</small>!!</a> </span>
  </div>
</marquee>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{URL("/")}}"><img src="{{("/images/comic_logo.png")}}" alt="logo" title="logo" class="logo">@if (Route::currentRouteName() =="avbodys.category" and $mobile==true ) - <small>{{$page['sub_title']}}</small> @endif</a>
        </div>

        <div id="navbar" class="navbar-collapse collapse ">

            <ul class="nav navbar-nav navbar-right">
                @if( Auth::av_user()->check())

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" rel="nofollow" role="button" aria-haspopup="true" aria-expanded="true">{{Auth::av_user()->get()->nick_name}}の漫畫 <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{route('av.user.profile')}}" data-target="#system_maintain"><i class="fa fa-user"></i>  個人資料</a></button>
                            </li>
                            @if (Route::currentRouteName()=='avbodies.show' and Auth::av_user()->check()==true)
                                @if ($collects->user_collects==NULL or !in_array($article->id,$collects->collect_ids))
                                    <li id="collect_{{$article->id}}" class="hidden"><a
                                                href="{{route('avbodies.show', ['id'=>$article->ez_map[0]->unique_id])}}" alt="{{$article->title}}">   <i class="fa fa-heart-o"></i>
                                            @if (mb_strlen(strip_tags($article->title))<20 )
                                                {!!strip_tags($article->title)!!}
                                            @else
                                                {!!mb_substr(strip_tags($article->title),0,19)!!}...
                                            @endif
                                        </a></li>
                                @endif
                            @endif
                        @if (!empty($collects))
                               <?php $i=0;
                                $my_collects=array_reverse($collects->articles);
                                $my_collects_count=count($my_collects);
                                ?>
                                @foreach( $my_collects as $k=>$collect)
                                    <?php $i++;?>
                                    <li id="collect_{{$collect->id}}"><a
                                                href="{{route('avbodies.show', ['id'=>$collect->ez_map[0]->unique_id])}}" alt="{{$collect->title}}">   <i class="fa fa-heart-o"></i>
                                            @if (mb_strlen(strip_tags($collect->title))<20 )
                                                {!!strip_tags($collect->title)!!}
                                            @else
                                                {!!mb_substr(strip_tags($collect->title),0,19)!!}...
                                            @endif
                                        </a></li>
                                    @if ($i==4) <?php break;?> @endif

                                @endforeach
                            @else
                                  @if ($collects->user_collects==NULL )
                                    <li id="collect_0" class="text-gray text-center">
                                       你尚未收藏任何漫畫
                                    </li>
                                @endif
                            @endif


                            @if ($my_collects_count>4 and !empty($collects))
                                <li>
                                    <a href="{{route('av.user.collect')}}" ><i class="fa fa-heart"></i>  更多收藏...</a>
                                </li>
                            @endif
                                <li><a data-toggle="modal" data-target="#system_maintain"><i class="fa fa-upload"></i>  我要分享</a></li>
                            <li><a href="{{route('avbodies.logout')}}" ><i class="fa fa-sign-out"></i>  登出</a></li>



                        </ul>

                    </li>

                @endif
                <li>
                @if( Auth::av_user()->check() )

                @else

                    <li class="dropdown" id="menuLogin">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown" title=" 會員獨享快速閱讀模式！完全免費！" id="navLogin">註冊 / 登入 <i class="fa  fa-sign-in"></i></a>


                        <div class="dropdown-menu" style="padding:17px;">
                            <a    href="{{route('avbodies.facebook.login')}}" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i>Facebook 註冊 / 登入</a>

                        </div>

                    </li>

                @endif

                </li>


            </ul>

        </div><!--/.nav-collapse -->
        @if( Auth::av_user()->check()===false  )
            <div class="avuser_tip" style="text-align: right" >會員獨享快速閱讀模式！完全免費！</div>
        @endif
        <div class="navbar-header">

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
    @if ($mobile==true and  Route::currentRouteName()!='avbodies.show' and  Route::currentRouteName()!='avbodies.tool')
    @include('_layouts/avbody/search')
    @endif
        <!-- Project One -->
        <div class="row main">

            @yield('content')

        </div>
        <!-- /.row -->


        <!-- Pagination -->

        <!-- /.row -->


        <!-- Footer -->


    <footer itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p>Copyright &copy; <a href="{{URL("/")}}" itemprop="url">AVBODY</a> {{date('Y')}}</p>
                    <div class="porn_tip">
                    <p>

                    「本網站已依網站內容分級規定處理」
                    <br>
                    警告︰此區只適合十八歲或以上人士觀看。<br>
                    此區內容可能令人反感；不可將此區的內容派發、傳閱、出售、出租、交給或借予年齡未滿18歲的人士或將本網站內容向該人士出示、播放或放映。
                    </p>
                    警告︰嚴禁發表人獸交、幼齒、兒童色情、暴力、血腥、變態及令人嘔心之圖片。
                        </div>
                </div>
            </div>
            <!-- /.row -->
        </footer>

</div>
<div class="modal fade" id="system_maintain" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
         <h2><i class="fa fa-meh-o"></i>系統維謢中</h2>
            </div>
            <div class="modal-footer">

            </div>

        </div>
    </div>
</div>
@if(Session::has('message'))
    <div class="alert alert-info alert-dismissable"  id="update_message">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ Session::get('message') }}
    </div>
@endif
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.min.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/localization/messages_zh_TW.js"></script>
<script src="{{('/js/carousel-swipe.js')}}"></script>


<SCRIPT>
    var site_name="AVBODY";
    var site_url="avbody.info";
    var title="AVBODY";
    var site_leave_url='javascript: history.go(-1)';


</SCRIPT>


<script src="{{('/js/jquery.lazyload.js')}}"></script>
<script src="{{('/js/gogo.js')}}"></script>
@if (isset($_GET['miu']) and $_GET['miu']==true)
@else
<script src="{{('/js/avbody.adultcheck.js')}}"></script>
@endif
<script src="{{('/bower_components/bootstrap-star-rating/js/star-rating.min.js')}}"></script>
<script src="{{('/bower_components/bootstrap-star-rating/js/star-rating_locale_tw.js')}}"></script>
<SCRIPT>

    $(function() {
        //$('.article-main img[src!=""]').lazyload({placeholder:'xxx.jpg'})

        $(".article-main img").lazyload({placeholder:'{{('/images/loading.png')}}'});
        //$(".article-main img").lazyload();
        //$(".adv").lazyload();
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip({placement:'bottom'})
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
                //  console.log(element.parent(),'aa');
                error.insertAfter(element.parent());

            }else  if (element.attr("name") != "avatar") {
                //  console.log(element.id,'bb');
                error.insertAfter(element);
            } else {
                console.log('avatar');
                error.insertAfter(".avatar-right");
            }
        }
    });

    $(".avbody_search").validate();

    setTimeout(function() {
        $('#update_message').fadeOut();
    }, 3000 );

</script>

@stack('scripts')

</body>

</html>
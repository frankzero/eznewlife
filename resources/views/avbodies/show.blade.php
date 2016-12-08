
@extends('_layouts/avbody')
@section('content')
    @if ($mobile==true)
        <div class="modal fade " tabindex="-1" role="dialog" id="ad_block_13">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body text-center">
                        @if(isset($_GET['ad'])) 手機蓋版廣告 :13 @endif
                        {!! get_adCode(13, $plan, __DOMAIN__) !!}
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @endif
    @if ($mobile==false)
        <div class="row ">
            <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_5 "
                 class="adv ">
                @if(isset($_GET['ad'])) logo下方pc廣告代號 : 5 @endif
                {!! get_adCode(5, $plan, __DOMAIN__) !!}<br></div>
        </div>
    @endif
    <div class="">
        <div class="col-lg-9" id="main-show-comic">

            <article class="article-main"  itemscope itemtype="http://schema.org/ImageGallery">
                <link itemprop="mainEntityOfPage" href="/{{$article->ez_map[0]->unique_id}}?page={!!Input::get('page')?Input::get('page'):1!!}" />
                <div class="body">
                    <h1  itemprop="headline name">{{ $article->title }}</h1>
                    <h2 class="hidden" itemprop="about">{{$page['title']}}</h2>
                    <time itemprop="dateModified" datetime="{{$article->updated_at_iso}}" class="hidden">{{$article->updated_at_iso}}</time>
                    <time itemprop="datePublished" datetime="{{$article->publish_at}}" class="hidden">{{substr($article->publish_at,0,10)}}</time>
                    <span itemprop="author" class="hidden">{{$article->author->name}}</span>
                    <span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                      <span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                        <meta itemprop="url" content="{{('/images/comic_logo.png')}}">
                      </span>
                      <meta itemprop="name" content="{{ $article->title }}">
                    </span>
                    <div class="meta">
                        <div class="row">
                            <div class="col-xs-12 ">
                                <?php

                                    $add="hidden";$cancel="hidden";
                                ?>
                                @if ( Auth::av_user()->check()===false )
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal">
                                        <i class="fa fa-heart-o"></i> 收藏
                                    </button>

                                @elseif (!empty($collects)  and  array_key_exists($article->id,$collects->collect_ids))
                                    <?php $add="hidden";$cancel="";       ?>
                                @else
                                        <?php $add="";$cancel="hidden";?>
                                @endif
                                @if ( Auth::av_user()->check()===true)
                                    <a href="#"  data-toggle="tooltip" title="點我,取消收藏"  id="cancel_collect"
                                       class="btn btn-default btn-sm collect {{$cancel}} "><i class="fa fa-heart text-danger"></i>已收藏</a>

                                    <a href="#"  data-toggle="tooltip" title="點我,收藏漫畫"  id="add_collect"
                                       class="btn btn-danger btn-sm collect  {{$add}} "><i class="fa fa-heart-o"></i> 收藏</a>
                                @else
                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog modal-sm" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h3 class="modal-title" id="myModalLabel"> AVBODY LOGIN</h3>
                                                </div>
                                                <div class="modal-body">
                                                    <strong>您還未登入，要現在登入嗎？</strong>
                                                    <br>

                                                    你必需成為會員, 加入會員，<u class="text-red">完全免費!!</u>
                                                    <ul> <li>獨享<span class="text-red">快速閱讀</span>模式</li><li><span class="text-red">收藏</span>漫畫</li>
                                                        <li><span class="text-red">評分</span>漫畫</li>

                                                    </ul>


                                                    <br><br>
                                                </div>


                                                <div class="modal-footer">
                                                    <a href="{{route('avbodies.facebook.login')}}" class="btn btn-social btn-facebook btn-lg btn-block "><i class="fa fa-facebook"></i>Facebook 註冊/登入</a>

                                                </div> </div>
                                        </div>
                                    </div>
                                @endif
                                <button type="button" class="btn btn-default bg-black btn-sm pull-right" data-toggle="modal" data-target="#report">
                                    <i class="fa fa-hand-paper-o"></i> 檢舉
                                </button>

                            </div>

                        </div>
                    </div>
                    <p></p>
                    <div class="ad-md">

                        <div  class="article-content " @if (($mobile==true and $parameter['clickme']=='1')and $article->content_page==1)style="display: none" @endif>
                            <span class="hidden">{{$article->title}}</div>
                            {!!$article->content!!}

                                    <!---評分機制---->

                            {!!Form::open(['route'=>['avbodies.vote',$article->id],'method'=>'post','id' => 'article_search','class'=>" form-inline article_search"])!!}
                             @if($rate_score >0)
                            <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="row">
                             @else
                            <div class="row">
                           @endif
                                <div class="col-lg-2 col-xs-12"><a href="#" class="btn-md btn text-bold  "
                                                                   style="margin-top:5px;color:#ffc933 !important;background-color: #1c1c1c !important;"> <i class="fa  fa-star"></i>
                                        @if($rate_score >0)<span id="score" itemprop="ratingValue">{{$rate_score}}</span>@else <span id="score" >{{$rate_score}}</span>@endif</a>
                                </div>
                                <div class="col-lg-10 col-xs-12">
                                    @if ( Auth::av_user()->check()===false )

                                        <input id="input-1" name="rating" data-toggle="modal" data-target="#myModal" class="pull-left rating rating-loading" data-min="0" data-max="5"
                                               data-step="1" readonly>
                                    @else
                                        <input id="input-1" name="rating" class="pull-left rating rating-loading" data-min="0" data-max="5"  data-step="1">
                                    @endif

                                </div>
                                <div id="rate_count" class="col-lg-offset-3 col-xs-offset-1 small text-muted">
                                    @if($rate_score >0)
                                        <span itemprop="ratingCount">{{$rate_count}}</span>
                                    @else
                                        <span>{{$rate_count}}</span>
                                    @endif
                                    人已投票</div>
                                <div id="final_msg" class="col-lg-offset-3  col-xs-offset-1"></div>
                            </div>


                            {!! Form::close() !!}
                        </span>
                    </div>
            </article>
        </div>
        <aside class="col-lg-3 " id="right-show-comic">
            @if ($mobile==false)
                @include('_layouts/avbody/search')
            @endif
            @include('_layouts/avbody/right')
        </aside>
    </div>
    @if ($mobile==true)

            <!--標題內文廣告 手機 PC 一樣的---->
    <div @if(isset($_GET['ad']))  style="background-color: #ffba00" @endif id="ad_block_6"
         class="adv w336">
        @if(isset($_GET['ad'])) 分頁上方手機廣告代號 : 6 @endif
        {!!  get_adCode(6, $plan, __DOMAIN__) !!}
    </div>

    @endif


    @if ($mobile==false)
        <div class="row " style="margin-bottom: -18px;">

            <div class="adv" @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_8 "
            >
                @if(isset($_GET['ad'])) 分頁上方pc廣告代號 : 8 @endif
                {!! get_adCode(8, $plan, __DOMAIN__) !!}<br></div>
        </div>
    @endif
    <div class="text-center center-block page_bar">     {!!$article->page_bar_scroll!!}     </div>





    @if ($mobile==true)

            <!--標題內文廣告 手機 PC 一樣的---->
    <div @if(isset($_GET['ad']))  style="background-color: #ffba00" @endif id="ad_block_9"
         class="adv w336">
        @if(isset($_GET['ad'])) 分頁上方手機廣告代號 : 9 @endif
        {!!  get_adCode(9, $plan, __DOMAIN__) !!}
    </div>

    @endif


    @if ($mobile==false)
        <div class="row ">

            <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_10 "
                 class="adv ">
                @if(isset($_GET['ad'])) 分頁上方pc廣告代號 : 10 @endif
                {!! get_adCode(10, $plan, __DOMAIN__) !!}<br></div>
        </div>

        <div class="col-lg-12 row" id="rand-main-comic">

            @foreach ($rand_articles as $k=> $rand_article)
                <div class=" col-lg-4 col-md-4 col-sm-6 col-xs-12 img-item"
                     onclick="window.location='/{{$rand_article->ez_map[0]->unique_id}}'">

                    <div class="hovereffect rand_img">
                        <a href="/{{$rand_article->ez_map[0]->unique_id}}">
                            @if (File::exists( public_path() . '/focus_photos'."/".$rand_article->photo) and !empty($rand_article->photo))
                                <img src="{!!("/focus_photos/400/".$rand_article->photo) !!}?lastmod={!!date("YmdH")!!}"
                                     class="img-responsive img-thumbnail center-block" alt="{{$rand_article->title}}">
                            @else
                                <img src="{!!("/images/nophoto.png") !!}"
                                     class="img-responsive img-thumbnail center-block" alt="{{$rand_article->title}}">
                            @endif
                        </a>
                        <div class="overlay">
                            <h2>
                                <a href="/{{$rand_article->ez_map[0]->unique_id}}">
                                    @if ((strlen(strip_tags($rand_article->title))-mb_strlen(strip_tags($rand_article->title))<10))
                                        {{---english---}}
                                        @if (strlen(strip_tags($rand_article->title))<90)
                                            {!!strip_tags($rand_article->title)!!}
                                        @else
                                            {!!substr(strip_tags($rand_article->title),0,89)!!}...
                                        @endif

                                    @else

                                        {{---chinese---}}
                                        @if (mb_strlen(strip_tags($rand_article->title))<50 )
                                            {!!strip_tags($rand_article->title)!!}
                                        @else
                                            {!!mb_substr(strip_tags($rand_article->title),0,49)!!}...
                                        @endif
                                    @endif
                                </a>
                            </h2>
                        </div>
                    </div>

                </div>

            @endforeach

        </div>
    @endif
    <div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <h4><p>謝謝您的回報</p>
                        ~ 我們會儘速處理 ~</h4>
                </div>
                <div class="modal-footer">

                </div>

            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script>
            @if ($mobile==true)
    var ad_window_time = parseInt('<?=fconfig('ad_window_time');?>');
    console.log(ad_window_time);
    function setCookie(key, value, h) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (h * 60 * 60 * 1000));
        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
    }

    function getCookie(key) {
        console.log('getCookie');
        var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
        return keyValue ? keyValue[2] : null;
    }


    if (getCookie('pop_sleep') == null && ad_window_time >= 0) {
        console.log('setCookie');
        ExpireCookie(1);
        $('#ad_block_13').modal('show');
    }

    function ExpireCookie(minutes) {
        var date = new Date();
        var m = minutes;
        setCookie('pop_sleep', 1, 1)
    }
            @endif
    var form = new FormData($('#form_step4')[0]);
    form.append('view_type', 'addtemplate');
    @if ( Auth::av_user()->check()===true )
    //$('.collect').click(function () {


    var requestRunning = false;
    $('.collect').on('touchstart click', function(e){
        if (requestRunning) { // don't do anything if an AJAX request is pending
            return;
        }
        requestRunning = true;
        $.ajax({
            type: "GET",
            url: "/collect/{{$article->id}}",
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.status == 0) {
                    //未收藏
                    $("#add_collect").removeClass("hidden");
                    $("#cancel_collect").addClass("hidden");
                    $("#collect_{{$article->id}}").addClass("hidden");
                    @if ( empty($collects) )
                        $("#collect_0").removeClass("hidden");
                    @endif
                } else {
                    //data.status == 1
                    //已收藏
                    $("#cancel_collect").removeClass("hidden");
                    $("#add_collect").addClass("hidden");
                    $("#collect_{{$article->id}}").removeClass("hidden");
                    @if ( empty($collects) )
                        $("#collect_0").addClass("hidden");
                    @endif
                }
                console.log(requestRunning);
                console.log("status=",data.status, data.message);

// alert("Settings has been updated successfully.");
//  window.location.reload(true);
            },
            failure: function (response, status) {
                console.log("failure", response, status);

// failure code here
            },
            complete: function (xhr, status) {
                console.log(xhr.status);
                requestRunning = false;
                //   console.log('解除');
                //  $(this).prop('disabled', false);
                // console.log('success');
// console.log(xhr, status);
// on complete code here
            }

        });

    });
    @endif
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
    $("#input-1").rating(
            {
                'size': 'xs',
                language: 'tw'

            }
    );
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    //$('.rating').click(function () {
    var scoreIng = false;
    $('.rating').on('touchstart click', function(){
        //  $('.rating').on('touchstart click', function(){ /* do something... */
        @if ( Auth::av_user()->check()===false )
            $('#myModal').modal('show');
        @else
        if (scoreIng) { // don't do anything if an AJAX request is pending
            return;
        }
        scoreIng = true;
        $.ajax({
            type: "POST",
            url: "vote/{{$article->id}}",
            data: {_token: CSRF_TOKEN,rating:$("#input-1").val()},

            success: function (data) {
                if (data.status == 0) {
//未收藏

                    //  $("#score").innerHTML=data.score;
                } else {

                }

                $('#final_msg').text(data.message).fadeIn().delay(3000).fadeOut();
                $("#score").text(data.score);
                $("#rate_count").text(data.rate_count+"人已投票");
                console.log(data.status, data.message);

                // alert("Settings has been updated successfully.");
                //  window.location.reload(true);
            },
            failure: function (response, status) {
                console.log("failure", response, status);

// failure code here
            },
            complete: function (xhr, status) {
                console.log(xhr.status);
                scoreIng=false;
// console.log(xhr, status);
// on complete code here
            }


        });
        @endif
    });

    $(window).scroll(function() {
        // var bottom = $(window).height() - $(".right_ad").outerHeight();
        //  bottom = bottom - $(".right_ad").offset().top;
        // buttom= $(window).scrollTop() + $(window).height();
        // console.log("right_bottom=" + bottom);
        // console.log($(window).scrollTop()+"height="+$(window).height());
        var height=$(window).height()
        var right_ad=$(".right_ad").outerHeight();
        var obj_bottom=right_ad-height;
        var main_height=$("#main-show-comic").outerHeight();
        var rand_height=$("#rand-main-comic").outerHeight();
        var bottom_top=main_height-1000;
        var bottom_height=main_height-rand_height;
        /* //stop scroll

         console.log("scroll="+$(window).scrollTop()+'windows height='+height+',bottom top='+bottom_top
         +',bottom height='+bottom_height+',main_height='+main_height+'rand height='+rand_height,',right_ad='+right_ad+',obj='+obj_bottom);*/
        // console.log($(this).scrollTop());
        //   console.log($("#main-dark").outerHeight() + "right"+$("#right-right").outerHeight());
        if ($("#main-show-comic").outerHeight()> $("#right-show-comic").outerHeight()){

            //  if ($(this).scrollTop() >=(height-60) && $(this).scrollTop() < (bottom_height+250)){
            if ($(this).scrollTop() >=150 && $(this).scrollTop() <main_height-760) {
                // console.log('window height='+height+',right_ad='+right_ad+',obj='+obj_bottom);
                var right_ad_width = $('.right_ad').outerWidth();
                console.log('fixed');
                $('.right_ad').css("top", '');
                $(".right_ad").addClass("right_ad_fixed");
                $('.right_ad').css("width", right_ad_width);
                // $('.right_ad').css("position",'fixed');
                //$(".right_ad").css("bottom",'-40px' );
            }else if ($(this).scrollTop() >=150 && $(this).scrollTop() < main_height){
                console.log('fixed_less,'+'bottom_top='+bottom_top);
                // $('.right_ad').css("top", '');
                // $('.right_ad').css("bottom", $(this).scrollTop()-1200);
                $('.right_ad').css("top", bottom_top-$(this).scrollTop());
                $('.right_ad').css("position","fixed");
            } else{
                $('.right_ad').css("position","");
                console.log('no-fixed');
                $('.right_ad').css("top", '');
                //     console.log('unfixed=height='+height+',right_ad='+right_ad+',obj='+obj_bottom);
                $(".right_ad").removeClass("right_ad_fixed" );
            }
        }
    });
</SCRIPT>
@endpush
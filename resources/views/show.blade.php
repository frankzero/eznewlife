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
    <div class="row">
        <div class="col-lg-9" id="main-show-comic">
            <article class="article-main">
                <div class="body">
                    <h1>{{ $article->title }}</h1>
                    <div class="meta">
                        <div class="row">
                            <div class="col-xs-12 ">
                                <?php
                                    //dd($collects->user_collects);
                                ?>
                                @if ( Auth::av_user()->check()===false )
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal">
                                            <i class="fa fa-heart-o"></i> 收藏
                                        </button>


                                @elseif ( in_array($article->id,$collects->user_collects->toArray()))
                                    <?php $add="hidden";$cancel=""?>
                                @else
                                   <?php $add="";$cancel="hidden"?>
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
                                                        <h3 class="modal-title" id="myModalLabel"><i class="fa fa-heart text-danger"></i> AVBODY LOGIN</h3>
                                                    </div>
                                                    <div class="modal-body">
                                                        <strong>您還未登入，要現在登入嗎？</strong>
                                                        <br>

                                                      你必需成為會員，我們才能幫你收藏喔!!<br>
                                                        成為AVBODY會員，完全免費呦!！

                                                        <br><br>
                                                    </div>


                                                <div class="modal-footer">
                                                    <a href="{{route('avbodies.facebook.login')}}" class="btn btn-social btn-facebook btn-lg btn-block "><i class="fa fa-facebook"></i>Facebook 註冊/登入</a>

                                                </div> </div>
                                            </div>
                                        </div>
                            @endif

</div>

</div>
</div>
<p></p>
<div class="ad-md">
<div class="article-content "
@if (($mobile==true and $parameter['clickme']=='1')and $article->content_page==1)style="display: none" @endif>
{!!$article->content!!}
</div>
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
<div class="row ">
<!--標題內文廣告 手機 PC 一樣的---->
<div @if(isset($_GET['ad']))  style="background-color: #ffba00" @endif id="ad_block_6"
class="adv w336">
@if(isset($_GET['ad'])) 分頁上方手機廣告代號 : 6 @endif
{!!  get_adCode(6, $plan, __DOMAIN__) !!}
</div>
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
<div class="row ">
<!--標題內文廣告 手機 PC 一樣的---->
<div @if(isset($_GET['ad']))  style="background-color: #ffba00" @endif id="ad_block_9"
class="adv w336">
@if(isset($_GET['ad'])) 分頁上方手機廣告代號 : 9 @endif
{!!  get_adCode(9, $plan, __DOMAIN__) !!}
</div>
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
onclick="window.location='{{route('avbodies.show', ['id'=>$rand_article->ez_map[0]->unique_id])}}'">

<div class="hovereffect rand_img">
<a href="{{route('avbodies.show', ['id'=>$rand_article->ez_map[0]->unique_id])}}">
@if (File::exists( public_path() . '/focus_photos'."/".$rand_article->photo) and !empty($rand_article->photo))
<img src="{!!("/focus_photos/400/".$rand_article->photo) !!}?lastmod={!!$rand_article->updated_at!!}"
     class="img-responsive img-thumbnail center-block" alt="{{$rand_article->title}}">
@else
<img src="{!!("/images/nophoto.png") !!}"
     class="img-responsive img-thumbnail center-block" alt="{{$rand_article->title}}">
@endif
</a>
<div class="overlay">
<h2>
<a href="{{route('avbodies.show', ['id'=>$rand_article->ez_map[0]->unique_id])}}">
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
$('.collect').click(function () {
    $.ajax({
        type: "GET",
        url: "{{route('avbodies.collect',$article->id)}}",
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.status == 0) {
//未收藏
                $("#add_collect").removeClass("hidden");
                $("#cancel_collect").addClass("hidden");
                $("#collect_{{$article->id}}").addClass("hidden");
                @if ($collects->count()==0)
                     $("#collect_0").removeClass("hidden");
                @endif
            } else {
                $("#cancel_collect").removeClass("hidden");
                $("#add_collect").addClass("hidden");
                $("#collect_{{$article->id}}").removeClass("hidden");
                @if ($collects->count()==0)
                     $("#collect_0").addClass("hidden");
                @endif
            }
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
// console.log(xhr, status);
// on complete code here
        }


    });
});
@endif
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
console.log('d: ' + $(document).height());
console.log('w: ' + $(window).height());
console.log('b: ' + $('body').height());
var body_h=$("#main-show-comic").height();
var bottom_top=body_h+450;

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
    var bottom_height=main_height-rand_height;
     console.log("scroll="+$(window).scrollTop()+'windows height='+height+',bottom height='+bottom_height+',main_height='+main_height+'rand height='+rand_height,',right_ad='+right_ad+',obj='+obj_bottom);
     console.log($(this).scrollTop());
    //   console.log($("#main-dark").outerHeight() + "right"+$("#right-right").outerHeight());
    if ($("#main-show-comic").outerHeight()> $("#right-show-comic").outerHeight()){

      //  if ($(this).scrollTop() >=(height-60) && $(this).scrollTop() < (bottom_height+250)){
        if ($(this).scrollTop() >=150 && $(this).scrollTop() <right_ad) {
            // console.log('window height='+height+',right_ad='+right_ad+',obj='+obj_bottom);
            var right_ad_width = $('.right_ad').outerWidth();
            console.log('fixed');
            $(".right_ad").addClass("right_ad_fixed");
            $('.right_ad').css("width", right_ad_width);
            // $('.right_ad').css("position",'fixed');
            //$(".right_ad").css("bottom",'-40px' );
        }else if ($(this).scrollTop() >=150 && $(this).scrollTop() < bottom_top){
            console.log('fixed_less,'+'bottom_top='+bottom_top);
            $('.right_ad').css("top", '');
           // $('.right_ad').css("bottom", $(this).scrollTop()-1200);
            $('.right_ad').css("top", 1200-$(this).scrollTop());
        } else{
            console.log('no-fixed');
            $('.right_ad').css("top", '');
            //     console.log('unfixed=height='+height+',right_ad='+right_ad+',obj='+obj_bottom);
            $(".right_ad").removeClass("right_ad_fixed" );
        }
    }
});
</SCRIPT>
@endpush
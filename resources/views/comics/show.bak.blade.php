@extends('_layouts/comic')
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
        <div class="col-lg-9 jzoom" id="main-show-comic">
            <article class="article-main">
                <div class="body">
                    <h1>{{ $article->title }}</h1>
                    <div class="meta">
                        <div class="row">
                            <div class="col-xs-12 ">
                                @if( Auth::check() )
                                    <a href="{{route('admin.articles.edit',['id'=>$article->id])}}"
                                       class="btn btn-warning btn-xs"><i class="fa fa-pencil-square-o"></i>編輯文章</a>
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
                @include('_layouts/comic/search')
            @endif
        @include('_layouts/comic/right')
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
        <div class="row ">

            <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_8 "
                 class="adv  ">
                @if(isset($_GET['ad'])) 分頁上方pc廣告代號 : 8 @endif
                {!! get_adCode(8, $plan, __DOMAIN__) !!}<br></div>
        </div>
    @endif
    <div>     {!!$article->page_bar_scroll!!}     </div>





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

            @foreach ($rand_articles as $k=> $article)
                <div class=" col-lg-4 col-md-4 col-sm-6 col-xs-12 img-item"
                     onclick="window.location='{{route('comics.show', ['id'=>$article->ez_map[0]->unique_id])}}'">

                    <div class="hovereffect rand_img">
                        <a href="{{route('comics.show', ['id'=>$article->ez_map[0]->unique_id])}}">
                            @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                                <img src="{!!cdn("focus_photos/400/".$article->photo) !!}?lastmod={!!$article->updated_at!!}"
                                     class="img-responsive img-thumbnail center-block" alt="{{$article->title}}">
                            @else
                                <img src="{!!cdn("images/nophoto.png") !!}"
                                     class="img-responsive img-thumbnail center-block" alt="{{$article->title}}">
                            @endif
                        </a>
                        <div class="overlay">
                            <h2>
                                <a href="{{route('comics.show', ['id'=>$article->ez_map[0]->unique_id])}}">
                                    @if ((strlen(strip_tags($article->title))-mb_strlen(strip_tags($article->title))<10))
                                        {{---english---}}
                                        @if (strlen(strip_tags($article->title))<90)
                                            {!!strip_tags($article->title)!!}
                                        @else
                                            {!!substr(strip_tags($article->title),0,89)!!}...
                                        @endif

                                    @else

                                        {{---chinese---}}
                                        @if (mb_strlen(strip_tags($article->title))<50 )
                                            {!!strip_tags($article->title)!!}
                                        @else
                                            {!!mb_substr(strip_tags($article->title),0,49)!!}...
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
<style>

    .article-content {
        display:inline-block;
        position: relative;
    }

    /* magnifying glass icon */
    .article-content:after {
        content:'';
        display:block;
        width:33px;
        height:33px;
        position:absolute;
        top:0;
        right:0;
        background:url({{cdn('images/icon.png')}});
    }

    .article-content img {
        display: block;
    }

    .article-content img::selection { background-color: transparent; }
    @media screen and (max-width: 768px) and (min-width: 320px){
      /*  .article-content {
            padding-left: 45px;
            padding-right: 79px;
            padding-bottom: 50px;;
        }*/
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-zoom/1.7.15/jquery.zoom.js"></script>
<script>
  //  @if ($mobile==true) var magnify=0.7; @endif
    @if ($mobile==true) var magnify=0.8;
  //  $(document).ready(function(){
   //     $('.article-content').zoom({magnify:magnify,on:'grab'});

  //  })
    @endif;
</script>
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

</SCRIPT>
@endpush
@extends('_layouts/comic')

@section('content')

    <div class="col-lg-9" id="main-comic">




    <div class="row center-block">
        @if ($mobile==true)
            <div class="alert  adv"
                 role="alert">

                <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_1"
                     class="  adv  text-center" style="min-width:100px;min-height:50px;margin:auto;padding:0;" role="alert">
                    @if(isset($_GET['ad'])) 手機版logo下方 : 1 @endif
                    {!! get_adCode(1, $plan, __DOMAIN__) !!}
                </div>

            </div>
        @endif
        @if ($mobile==false)
            <div class="row">

                <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_2 "
                     class="adv ">
                    @if(isset($_GET['ad'])) 電腦版logo下方 :2 @endif
                    {!! get_adCode(2, $plan, __DOMAIN__) !!}<br></div>
            </div>
        @endif

        @foreach ($articles as $k=> $article)
            <div class=" col-lg-4 col-md-4 col-sm-6 col-xs-12 img-item"
                 onclick="window.location='{{route('comics.show', ['id'=>$article->ez_map[0]->unique_id])}}'">

                    <div class="hovereffect index_img">
                        <a href="{{route('comics.show', ['id'=>$article->ez_map[0]->unique_id])}}">
                            @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                                <img src="{!!asset("focus_photos/400/".$article->photo) !!}?lastmod={!!$article->updated_at!!}"
                                     class="img-responsive img-thumbnail center-block" alt="{{$article->title}}">
                            @else
                                <img src="{!!asset("images/nophoto.png") !!}"
                                     class="img-responsive img-thumbnail center-block" alt="{{$article->title}}">
                            @endif
                        </a>
                        <div class="overlay">
                            <h2> <a href="{{route('comics.show', ['id'=>$article->ez_map[0]->unique_id])}}">
                                    @if ((strlen(strip_tags($article->title))-mb_strlen(strip_tags($article->title))<10))
                                        {{---english---}}
                                        @if (strlen(strip_tags($article->title))<70)
                                            {!!strip_tags($article->title)!!}
                                        @else
                                            {!!substr(strip_tags($article->title),0,69)!!}...
                                        @endif

                                    @else

                                        {{---chinese---}}
                                        @if (mb_strlen(strip_tags($article->title))<35 )
                                            {!!strip_tags($article->title)!!}
                                        @else
                                            {!!mb_substr(strip_tags($article->title),0,34)!!}...
                                        @endif
                                    @endif

                                </a></h2>
                        </div>
                    </div>

            </div>

        @endforeach

    </div>
    @if ($mobile==true)
        <div class="alert  adv"
             role="alert">

            <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_3"
                 class="  adv  text-center" style="min-width:100px;min-height:50px;margin:auto;padding:0;" role="alert">
                @if(isset($_GET['ad'])) 手機版分頁上方 :3 @endif
                {!! get_adCode(3, $plan, __DOMAIN__) !!}
            </div>

        </div>
    @endif
    @if ($mobile==false)
        <div class="row">

            <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_4 "
                 class="adv ">
                @if(isset($_GET['ad'])) 電腦版分頁上方 :4 @endif
                {!! get_adCode(4, $plan, __DOMAIN__) !!}<br></div>
        </div>
    @endif
    <div class="text-center">
        {!!show_page_bar_scroll($articles,'btn-default btn-lg bg-black')!!}
    </div>
    </div>
    <aside class="col-lg-3 " id="right-comic">
        @if ($mobile==false)
        @include('_layouts/comic/search')
        @endif
        @include('_layouts/comic/right')
    </aside>
@endsection

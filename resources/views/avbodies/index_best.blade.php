@foreach ($best_articles as $k=> $article)
    <div class=" col-lg-4 col-md-4 col-sm-6 col-xs-12 img-item"
         onclick="window.location='/{{$article->ez_map[0]->unique_id}}'"  itemscope itemtype="http://schema.org/ImageObject">

        <div class="hovereffect index_img">
            <a href="/{{$article->ez_map[0]->unique_id}}">
                @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                    <img src="{!!("/focus_photos/400/".$article->photo) !!}?lastmod={!!date("YmdH")!!}"  itemprop="contentUrl"
                         class="img-responsive img-thumbnail center-block" alt="{{$article->title}}">
                @else
                    <img src="{!!("/images/nophoto.png") !!}"  itemprop="contentUrl"
                         class="img-responsive img-thumbnail center-block" alt="{{$article->title}}">
                @endif
            </a>
            @if(substr($best_article->publish_at,0,10)!='0000-00-00') <span itemprop="datePublished" class="hidden" content="{!!substr($best_article->publish_at,0,10)!!}"> {!!substr($best_article->publish_at,0,10)!!}</span> @endif
            <span itemprop="author" class="hidden">{!!$best_article->author->name!!}</span>
            <div class="overlay">
                <h2   itemprop="name"> <a href="/{{$article->ez_map[0]->unique_id}}" >
                        @if ((strlen(strip_tags($article->title))-mb_strlen(strip_tags($article->title))<10))
                            {{---english---}}
                            @if (strlen(strip_tags($article->title))<60)
                                {!!strip_tags($article->title)!!}
                            @else
                                {!!substr(strip_tags($article->title),0,59)!!}...
                            @endif

                        @else

                            {{---chinese---}}
                            @if (mb_strlen(strip_tags($article->title))<30 )
                                {!!strip_tags($article->title)!!}
                            @else
                                {!!mb_substr(strip_tags($article->title),0,29)!!}...
                            @endif
                        @endif

                    </a></h2>
            </div>
        </div>

    </div>

@endforeach
@if ($mobile==true)
    <div class="alert  adv col-lg-12"
         role="alert">

        <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_3"
             class="  adv  text-center" style="min-width:100px;min-height:50px;margin:auto;padding:0;" role="alert">
            @if(isset($_GET['ad'])) 手機版分頁上方 :3 @endif
            {!! get_adCode(3, $plan, __DOMAIN__) !!}
        </div>

    </div>
@endif
@if ($mobile==false)
    <div class="col-lg-12">

        <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_4 "
             class="adv ">
            @if(isset($_GET['ad'])) 電腦版分頁上方 :4 @endif
            {!! get_adCode(4, $plan, __DOMAIN__) !!}<br></div>
    </div>
@endif
<div class="text-center ">
    {!!show_page_bar_scroll($best_articles->appends(['tab' => 'best','new_page'=>Input::get('new_page')]),'btn-default btn-lg bg-black')!!}
</div>
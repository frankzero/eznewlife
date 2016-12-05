@if ($mobile==true)


    <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_9" class="w336  text-center" style="min-width:100px;min-height:50px;margin:auto;padding:0;" role="alert">
        @if(isset($_GET['ad'])) 廣告代號 :9 @endif
        {!! get_adCode(9, $plan, __DOMAIN__) !!}
    </div>

@else
    <div class="alert  alert-dismissible  aside-widget center-block right_ad  adv"
         role="alert">

        <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_6" class="  adv  text-center" style="min-width:100px;min-height:50px;margin:auto;padding:0;" role="alert">
            @if(isset($_GET['ad'])) 300x600廣告代號 : 6 @endif
            {!! get_adCode(6, $plan, __DOMAIN__) !!}
        </div>

    </div>
@endif
@if ($mobile==false)
    <div class="rand-title row"><i class="rand-icon fa fa-file-text-o bg-red"></i> <h4>隨機文章</h4></div>

    @for ($i = 0; $i < 3; $i++)
        <div class="row center-block  "
             onclick="window.location='{{route('getezs.show', ['id'=>$rand_articles[$i]->ez_map[0]->unique_id])}}'">
            <div class="thumbnail rand_hover " itemscope itemtype="http://schema.org/ImageObject">


                <a href="{{route('getezs.show', ['id'=>$rand_articles[$i]->ez_map[0]->unique_id])}}">
                    @if (File::exists( public_path() . '/focus_photos'."/".$rand_articles[$i]->photo) and !empty($rand_articles[$i]->photo))
                        <img src="{!!("/focus_photos/400/".$rand_articles[$i]->photo) !!}?lastmod={!!date("YmdH")!!}" itemprop="contentUrl"
                             class="img-responsive img-thumbnail" alt="{{$rand_articles[$i]->title}}">
                    @else
                        <img src="{!!("/images/nophoto.png") !!}"
                             class="img-responsive img-thumbnail" alt="{{$rand_articles[$i]->title}}" itemprop="contentUrl">
                    @endif
                </a>

                <div class="caption text-center">


                        <a href="{{route('getezs.show', ['id'=>$rand_articles[$i]->ez_map[0]->unique_id])}}">
                            <h3   itemprop="name">
                                @if ((strlen(strip_tags($rand_articles[$i]->title))-mb_strlen(strip_tags($rand_articles[$i]->title))<5))
                                    {{---english---}}
                                    @if (strlen(strip_tags($rand_articles[$i]->title))<49)
                                        {!!strip_tags($rand_articles[$i]->title)!!}
                                    @else
                                        {!!substr(strip_tags($rand_articles[$i]->title),0,48)!!}...
                                    @endif
                                @else
                                    {{---chinese---}}
                                    @if (mb_strlen(strip_tags($rand_articles[$i]->title))<18)
                                        {!!strip_tags($rand_articles[$i]->title)!!}
                                    @else
                                        {!!mb_substr(strip_tags($rand_articles[$i]->title),0,17)!!}...
                                    @endif
                                @endif
                            </h3></a>
                            @if(substr($rand_articles[$i]->publish_at,0,10)!='0000-00-00') <span itemprop="datePublished" class="hidden" content="{!!substr($rand_articles[$i]->publish_at,0,10)!!}"> {!!substr($rand_articles[$i]->publish_at,0,10)!!}</span> @endif
                            <span itemprop="author" class="hidden">{!!$rand_articles[$i]->author->name!!}</span>
                            <span itemprop="description" class="hidden">{!!$rand_articles[$i]->summary!!}</span>

                </div>
            </div>
            <div>
    @endfor
@endif
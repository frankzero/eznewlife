<!--
應用 index,search ,tag ,category
只有 內容頁 show 的右邊區塊不太一樣 -->

@if ($mobile==true)
    <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_9" class="w336  text-center"
         style="min-width:100px;min-height:50px;margin:auto;padding:0;" role="alert">
        @if(isset($_GET['ad'])) 廣告代號 :9 @endif
        {!! get_adCode(9, $plan, __DOMAIN__) !!}
    </div>

@else
    <div class="alert  alert-dismissible  aside-widget center-block right_ad adv"
         role="alert">

        <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_6" class="  adv  text-center"
             style="min-width:100px;min-height:50px;margin:auto;padding:0;" role="alert">
            @if(isset($_GET['ad'])) 300x600廣告代號 : 6 @endif
            {!! get_adCode(6, $plan, __DOMAIN__) !!}
        </div>
        @if ($mobile==false)

            <div class="rand-title row"><i class="rand-icon fa fa-file-text-o bg-purple"></i> <h4>隨機文章</h4></div>

            @for ($i = 0; $i < 1; $i++)
                <div class="row center-block  "
                     onclick="window.location='/{{$rand_articles[$i]->ez_map[0]->unique_id}}' "></div>
                    <div class="thumbnail rand_hover "  itemscope itemtype="http://schema.org/ImageObject">


                        <a href="/{{$rand_articles[$i]->ez_map[0]->unique_id}}">
                            @if (File::exists( public_path() . '/focus_photos'."/".$rand_articles[$i]->photo) and !empty($rand_articles[$i]->photo))
                                <img src="{!!("/focus_photos/400/".$rand_articles[$i]->photo) !!}?lastmod={{date("YmdH")}}" itemprop="contentUrl"
                                     class="img-responsive img-thumbnail right-thumbnail"
                                     alt="{{$rand_articles[$i]->title}}">
                            @else
                                <img src="{!!("/images/nophoto.png") !!}"   itemprop="contentUrl"
                                     class="img-responsive img-thumbnail right-thumbnail"
                                     alt="{{$rand_articles[$i]->title}}">
                            @endif
                        </a>
                        @if(substr($rand_articles[$i]->publish_at,0,10)!='0000-00-00') <span itemprop="datePublished" class="hidden" content="{!!substr($rand_articles[$i]->publish_at,0,10)!!}"> {!!substr($rand_articles[$i]->publish_at,0,10)!!}</span> @endif
                        <span itemprop="author" class="hidden">{!!$rand_articles[$i]->author->name!!}</span>
                        <span itemprop="description" class="hidden">{!!$rand_articles[$i]->summary!!}</span>
                        <div class="caption text-center">
                            <h3>

                                <a
                                        href="/{{$rand_articles[$i]->ez_map[0]->unique_id}}" itemprop="name">
                                    <strong>
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
                                    </strong></a>
                            </h3>
                        </div>
                    </div>
                    <div>
            @endfor
        @endif
         </div>
@endif

<aside class="col-md-3 blog-aside" id="right-enl" style="    width: 300px; padding:0px;padding-top: 36px">
    @include('_layouts/enl/search')
    <div  @if(isset($_GET['ad']))title="ad4"  style="background-color: #ffba00" data-toggle="tooltip" @endif id="ad_block_4" class=""  style="text-align:center;" role="alert">
        {!! get_adCode(4, $plan, __DOMAIN__) !!}
    </div>
    <hr>
    @if(isset($rand_articles))
        <div class="aside-widget-new list-group" id="right_rand" >
            @for ($i = 0; $i < 3; $i++)
                <a class="" href="{{route('articles.show', ['id'=>$rand_articles[$i]->ez_map[0]->unique_id,'title'=>hyphenize($rand_articles[$i]->title)],false)}}" style="height: 25%">
                    <figure class=" cap-bot" >
                        @if (File::exists( public_path() . '/focus_photos'."/400/".$rand_articles[$i]->photo) and !empty($rand_articles[$i]->photo))
                            <img  src="{!!("/focus_photos/400/".$rand_articles[$i]->photo) !!}?lastmod={!!date("YmdH")!!}" class="img-responsive img-thumbnail img-middle" alt="{{$rand_articles[$i]->title}}">
                        @else
                            <img src="{!!("/images/nophoto.png") !!}"   class="img-responsive img-thumbnail img-middle"  alt="{{$article->title}}">

                        @endif
                            <figcaption>
                        @if ((strlen(strip_tags($rand_articles[$i]->title))-mb_strlen(strip_tags($rand_articles[$i]->title))<10))
                            {{---english---}}
                            @if (strlen(strip_tags($rand_articles[$i]->title))<50)
                                {!!strip_tags($rand_articles[$i]->title)!!}
                            @else
                                {!!substr(strip_tags($rand_articles[$i]->title),0,49)!!}...
                            @endif

                        @else
                            {{---chinese---}}
                            @if (mb_strlen(strip_tags($rand_articles[$i]->title))<29)
                                {!!strip_tags($rand_articles[$i]->title)!!}
                            @else
                                {!!mb_substr(strip_tags($rand_articles[$i]->title),0,28)!!}...
                            @endif
                        @endif
                            </figcaption>

                    </figure>
                </a>
            @endfor
        </div>
    @endif
</aside>
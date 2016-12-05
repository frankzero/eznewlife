<aside class="col-md-3 blog-aside">
    @include('_layouts/enl/search')
    <div  @if(isset($_GET['ad']))title="ad4"  style="background-color: #ffba00" data-toggle="tooltip" @endif id="ad_block_4" class=""  style="text-align:center;" role="alert">
        {!! get_adCode(4, $plan, __DOMAIN__) !!}
    </div>

    @if(isset($rand_articles))
        <hr>
        <div class="aside-widget list-group" id="right_rand">

            @for ($i = 0; $i < 5; $i++)
                <a class="list-group-item list-group-item-black " href="{{route('articles.show', ['id'=>$rand_articles[$i]->ez_map[0]->unique_id,'title'=>hyphenize($rand_articles[$i]->title)])}}" style="height: 25%">
                    <h4 class="list-group-item-heading" >
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
                    </h4>
                </a>
            @endfor



        </div>
    @endif


</aside>
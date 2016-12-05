
        @foreach ($other_articles as $k=> $article)
            @if ($k<4)

            @endif
            <div class="row center-block article-other-row" onclick="window.location='{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)])}}'" itemscope itemtype="http://schema.org/ImageObject">
                {{---<code> {{$other_articles->currentPage()}}  // {{$other_articles->hasMorePages()}}</code>--}}
                <div class="col-md-6 col-sm-6 col-lg-6">


                    <a href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)],false)}}">
                        @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                            <img  src="{!!("/focus_photos/400/".$article->photo) !!}?lastmod={!!date("YmdH")!!}" class="img-responsive img-thumbnail img-middle" alt="{{$article->title}}" itemprop="contentUrl" >
                        @else
                            <img src="{!!("/images/nophoto.png") !!}"  itemprop="contentUrl"  class="img-responsive "  alt="{{$article->title}}">
                        @endif
                    </a>

                </div>

                <div class="col-md-6 col-sm-6 col-lg-6">

                    <div class="article-other-text" style="height: 100%">
                        <div class="body"> <h2 itemprop="name"><a href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)],false)}}"><strong>
                                        @if ((strlen(strip_tags($article->title))-mb_strlen(strip_tags($article->title))<5))
                                            {{---english---}}
                                            @if (strlen(strip_tags($article->title))<51)
                                                {!!strip_tags($article->title)!!}
                                            @else
                                                {!!substr(strip_tags($article->title),0,50)!!}...
                                                @endif

                                                @else
                                                        {{---chinese---}}
                                                @if (mb_strlen(strip_tags($article->title))<25)
                                                    {!!strip_tags($article->title)!!}
                                                @else
                                                    {!!mb_substr(strip_tags($article->title),0,24)!!}...
                                                @endif
                                            @endif
                                    </strong></a></h2> @if(substr($article->publish_at,0,10)!='0000-00-00') <span itemprop="datePublished" class="hidden" content="{!!substr($article->publish_at,0,10)!!}"> {!!substr($article->publish_at,0,10)!!}</span> @endif
                            <span itemprop="author" class="hidden">{!!$article->author->name!!}</span>

                            @if ($article->tags->pluck('name')->count()>0)
                                <div class="row_tag">
                                    <div class="col-xs-2"> <i class="fa  fa-tag"></i></div>
                                    <div class="col-xs-10 tag_hidden" >

                                        @foreach ($article->tags->pluck('name')->all() as $key =>$tag_name)
                                            <a href="{{route('articles.tag',['name'=>$tag_name],false)}}" class="btn btn-xs btn-default">{!!$tag_name!!}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <HR/>
                        <div class="body rand-content">
                            <a href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)],false)}}" itemprop="description">
                                @if ((strlen(strip_tags($article->summary))-mb_strlen(strip_tags($article->summary))<10))
                                    {{---english---}}
                                    @if (strlen(strip_tags($article->summary))<30)
                                        {!!strip_tags($article->summary)!!}
                                    @else
                                        {!!substr(strip_tags($article->summary),0,29)!!}...
                                        @endif

                                        @else
                                                {{---chinese---}}
                                        @if (mb_strlen(strip_tags($article->summary))<15)
                                            {!!strip_tags($article->summary)!!}
                                        @else
                                            {!!mb_substr(strip_tags($article->summary),0,14)!!}...
                                        @endif
                                    @endif
                            </a></div>
                    </div>

                </div>
            </div>

        @endforeach
        @if ($other_articles->hasMorePages()==false)
            <script>
                $("#other_stop").val(true);
                $(".article-other-main").append('<div style="margin-bottom: 50px"></div>');
            </script>
        @endif
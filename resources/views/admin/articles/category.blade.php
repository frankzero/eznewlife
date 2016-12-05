@extends('_layouts/front')
@section('content')
    <div class="col-md-8 blog-main">

        <div class="row center-block article-category-main  hover11 column">
            @foreach ($cate_articles as $k=> $article)

                <div class="row center-block article-category-row" onclick="window.location='{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)])}}'">
                    <div class="col-md-6 col-sm-6 col-lg-6 article-category-img ">


                        <a href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)])}}">
                            @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                                <img src="{!!("/focus_photos/400/".$article->photo) !!}?lastmod={!!$article->updated_at!!}"
                                     class="img-responsive img-thumbnail img-middle-category" alt="{{$article->title}}">
                            @else
                                <img src="{!!("/images/nophoto.png") !!}"
                                     class="img-responsive img-thumbnail" alt="{{$article->title}}">
                            @endif
                        </a>

                    </div>

                    <div class="col-md-6 col-sm-6 col-lg-6">

                        <div class="article-category-text">

                            @if ($article->tags->pluck('name')->count()>0)
                                <div class="row_tag">
                                    <div class="col-xs-1"> <i class="fa  fa-tag"></i></div>
                                    <div class="col-xs-11 tag_hidden" >

                                        @foreach ($article->tags->pluck('name')->all() as $key =>$tag_name)
                                            <a href="{{route('articles.tag',['name'=>$tag_name])}}" class="btn btn-xs btn-default ">{!!$tag_name!!}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="head"><h4><a
                                            href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)])}}">
                                        <strong>
                                            @if ((strlen(strip_tags($article->title))-mb_strlen(strip_tags($article->title))<5))
                                                {{---english--}}
                                                @if (strlen(strip_tags($article->title))<67)
                                                    {!!strip_tags($article->title)!!}
                                                @else
                                                    {!!substr(strip_tags($article->title),0,66)!!}...
                                                    @endif

                                                    @else
                                                            {{---chinese--}}
                                                    @if (mb_strlen(strip_tags($article->title))<29)
                                                        {!!strip_tags($article->title)!!}
                                                    @else
                                                        {!!mb_substr(strip_tags($article->title),0,28)!!}...
                                                    @endif
                                                @endif
                                        </strong></a></h4>
                            </div>
                            <HR/>
                            <div class="body rand-content">
                                <a href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)])}}">

                                    @if ((strlen(strip_tags($article->summary))-mb_strlen(strip_tags($article->summary))<10))
                                        {{---english--}}
                                        @if (strlen(strip_tags($article->summary))<112)
                                            {!!strip_tags($article->summary)!!}
                                        @else
                                            {!!substr(strip_tags($article->summary),0,111)!!}...
                                        @endif

                                   @else

                                                    {{---chinese--}}
                                        @if (mb_strlen(strip_tags($article->summary))<51)
                                            {!!strip_tags($article->summary)!!}
                                        @else
                                            {!!mb_substr(strip_tags($article->summary),0,50)!!}...
                                        @endif
                                  @endif
                                </a></div>
                        </div>

                    </div>
                </div>
                @if ($k<9)

                @endif
            @endforeach

        </div>
        <article class="ad-lg">
            <div class="row">
                <div  @if(isset($_GET['ad']))title="ad5" style="background-color: #ffba00" data-toggle="tooltip"  @endif id="ad_block_5" class="text-center" style="min-width:100px; min-height:100px;" role="alert">
                    {!! get_adCode(5, $plan, __DOMAIN__) !!}
                </div>
            </div>

        </article>
        <div class="row text-center">
            <div class="col-offset-6">{!!$cate_articles->render()!!}</div>
        </div>
        <article class="ad-lg">
            <div class="row">
                <div  @if(isset($_GET['ad']))title="ad6" style="background-color: #ffba00" data-toggle="tooltip"  @endif id="ad_block_6" class="text-center" style="min-width:100px; min-height:100px;" role="alert">
                    {!! get_adCode(6, $plan, __DOMAIN__) !!}
                </div>
            </div>

        </article>

    </div>


@endsection

@extends('_layouts/dark')
@section('content')
    @if ($mobile==true)
        <div class="alert  adv"   role="alert">
            <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_8"
                 class="  adv  text-center" style="min-width:100px;min-height:50px;margin:auto;padding:0;" role="alert">
                @if(isset($_GET['ad'])) 廣告代號 : 8 @endif
                {!! get_adCode(8, $plan, __DOMAIN__) !!}
            </div>
        </div>
    @endif
    <h1 id="index" class="hidden">Dark 最新文章列表</h1>
    <div class="row center-block article-category-main  hover11 column">
        @foreach ($articles as $k=> $article)
            <div class="row center-block article-category-row"  onclick="window.location='/{{$article->ez_map[0]->unique_id}}'"  itemscope itemtype="https://schema.org/ImageObject">
                <div class="col-md-6 col-sm-6 col-lg-6 article-category-img " >
                    <a href="/{{$article->ez_map[0]->unique_id}}">
                        @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                            <img src="{!!("/focus_photos/400/".$article->photo) !!}?lastmod={!!date("YmdH")!!}" itemprop="contentUrl"  class="img-responsive img-thumbnail center-block" alt="{{$article->title}}">
                        @else
                            <img src="{!!("/images/nophoto.png") !!}"  class="img-responsive img-thumbnail center-block" itemprop="contentUrl" alt="{{$article->title}}">
                        @endif
                    </a>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6">
                    <div class="article-category-text">
                        <div class="head "><!---enl /chinese-------->

                            <h2 itemprop="name"><a href="/{{$article->ez_map[0]->unique_id}}" >
                                        @if ((strlen(strip_tags($article->title))-mb_strlen(strip_tags($article->title))<5))
                                            @if (strlen(strip_tags($article->title))<67)
                                                {!!strip_tags($article->title)!!}
                                            @else
                                                {!!substr(strip_tags($article->title),0,66)!!}...
                                            @endif
                                        @else
                                            @if (mb_strlen(strip_tags($article->title))<29)
                                                {!!strip_tags($article->title)!!}
                                            @else
                                                {!!mb_substr(strip_tags($article->title),0,28)!!}...
                                            @endif
                                        @endif
                                   </a></h2>
                            @if(substr($article->publish_at,0,10)!='0000-00-00') <span itemprop="datePublished" class="hidden" content="{!!substr($article->publish_at,0,10)!!}"> {!!substr($article->publish_at,0,10)!!}</span> @endif
                            <span itemprop="author" class="hidden">{!!$article->author->name!!}</span>

                            @if ($article->tags->pluck('name')->count()>0)
                                <div class="row">
                                    <div class="col-xs-12 tag_hidden">
                                        @foreach ($article->tags->pluck('name')->all() as $key =>$tag_name)
                                            <a href="/tag/{{$tag_name}}" class="btn btn-xs bg-orange btn-flat" alt="ENL暗黑網-{!!$tag_name!!}" rel="tag">{!!$tag_name!!}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                        <HR/>
                        <div class="body rand-content">
                            <a href="/{{$article->ez_map[0]->unique_id}}" itemprop="description">
                                @if ((strlen(strip_tags($article->summary))-mb_strlen(strip_tags($article->summary))<10))
                                    {{---english---}}
                                    @if (strlen(strip_tags($article->summary))<112)
                                        {!!strip_tags($article->summary)!!}
                                    @else
                                        {!!substr(strip_tags($article->summary),0,111)!!}...
                                    @endif
                                @else
                                    {{---chinese---}}
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
    <div class="text-center mar-top15">
        {!!show_page_bar_scroll($articles,'btn-default btn-lg bg-black')!!}
    </div>
@endsection
@section('right_content')
@include('_layouts/dark/right')
@endsection
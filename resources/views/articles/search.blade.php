@extends('_layouts/enl')
@section('content')
    <div class="col-md-8 blog-main">
        <div class="alert alert-warning bg-orange ">字串「{{$q}}」搜尋結果</div>
        @if ($articles->count()==0) <div class="alert alert-default" >查無「{{$q}}」相關文章 </div>@endif
        <div class="row center-block article-category-main  hover11 column">
            @foreach ($articles as $k=> $article)

                <div class="row center-block article-category-row"
                     onclick="window.location='{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)],false)}}'" itemscope itemtype="http://schema.org/ImageObject">
                    <div class="col-md-6 col-sm-6 col-lg-6 article-category-img ">


                        <a href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)],false)}}">
                            @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                                <img src="{!!("/focus_photos/400/".$article->photo) !!}?lastmod={!!date("YmdH")!!}"  itemprop="contentUrl"
                                     class="img-responsive img-thumbnail" alt="{{$article->title}}">
                            @else
                                <img src="{!!("/images/nophoto.png") !!}"  itemprop="contentUrl"
                                     class="img-responsive img-thumbnail" alt="{{$article->title}}">
                            @endif
                        </a>

                    </div>
                    @if(substr($article->publish_at,0,10)!='0000-00-00') <span itemprop="datePublished" class="hidden" content="{!!substr($article->publish_at,0,10)!!}"> {!!substr($article->publish_at,0,10)!!}</span> @endif
                    <span itemprop="author" class="hidden">{!!$article->author->name!!}</span>

                    <div class="col-md-6 col-sm-6 col-lg-6">

                        <div class="article-category-text">
                            @if ($article->tags->pluck('name')->count()>0)
                                <div class="row_tag">
                                    <div class="col-xs-1"><i class="fa  fa-tag"></i></div>
                                    <div class="col-xs-11 tag_hidden">


                                        @foreach ($article->tags->pluck('name')->all() as $key =>$tag_name)
                                            <a href="{{route('articles.tag',['name'=>$tag_name],false)}}" rel="tag"
                                               class="btn btn-xs btn-default  @if ($tag_name==$page['sub_title']) bg-olive @endif">{!!$tag_name!!}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="head">
                                        <h2  itemprop="name"><a
                                            href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)],false)}}">
                                        <strong>
                                            @if ((strlen(strip_tags($article->title))-mb_strlen(strip_tags($article->title))<5))
                                                {{---english---}}
                                                @if (strlen(strip_tags($article->title))<67)
                                                    {!!str_replace($q,'<span class="text-mark">'.$q.'</span>',strip_tags($article->title))!!}
                                                @else
                                                    {!!str_replace($q,'<span class="text-mark">'.$q.'</span>',substr(strip_tags($article->title),0,66))!!}...
                                                @endif

                                            @else
                                                {{---chinese---}}
                                                @if (mb_strlen(strip_tags($article->title))<29)
                                                    {!!str_replace($q,'<span class="text-mark">'.$q.'</span>',strip_tags($article->title))!!}
                                                @else
                                                    {!!str_replace($q,'<span class="text-mark">'.$q.'</span>',mb_substr(strip_tags($article->title),0,28))!!}...
                                                @endif
                                            @endif
                                        </strong></a>

                                        </h2>
                            </div>

                            <HR/>
                            <div class="body rand-content">

                                <a href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)],false)}}" itemprop="description">

                                    @if ((strlen(strip_tags($article->content))-mb_strlen(strip_tags($article->content))<10))
                                        {{---english---}}
                                        @if (strlen(strip_tags($article->content))<112)
                                            {!!str_replace($q,'<span class="text-mark">'.$q.'</span>',strip_tags($article->content))!!}
                                        @else
                                            {!!str_replace($q,'<span class="text-mark">'.$q.'</span>',substr(strip_tags($article->content),0,111))!!}...
                                        @endif

                                    @else

                                        {{---chinese---}}
                                        @if (mb_strlen(strip_tags($article->content))<51)
                                            {!!str_replace($q,'<span class="text-mark">'.$q.'</span>',strip_tags($article->content))!!}
                                        @else
                                            {!!str_replace($q,'<span class="text-mark">'.$q.'</span>',mb_substr(strip_tags($article->content),0,50))!!}...
                                        @endif
                                    @endif
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                @if ($k<9)

                @endif
            @endforeach

        </div>
        <article class="ad-lg">
            <div class="row">
                <div  @if(isset($_GET['ad']))title="ad5" style="background-color: #ffba00" data-toggle="tooltip"  @endif id="ad_block_5" class="text-center" style="min-width:100px; min-height:100px;"
                     role="alert">
                    {!! get_adCode(5, $plan, __DOMAIN__) !!} 
                </div>
            </div>

        </article>
        <div class="row text-center">
            <div class="col-lg-offset-1">
                {!!show_page_bar_scroll($articles->appends(['q' => $q]),'btn-success btn-lg bg-enl')!!}
            </div>
        </div>
        <article class="ad-lg">
            <div class="row">
                <div  @if(isset($_GET['ad']))title="ad6" style="background-color: #ffba00" data-toggle="tooltip"  @endif id="ad_block_6" class="text-center" style="min-width:100px; min-height:100px;"
                     role="alert">
                    {!! get_adCode(6, $plan, __DOMAIN__) !!}
                </div>
            </div>

        </article>

    </div>


@endsection
@push('scripts')
<script>
    function hiliter(term, src_str) {
//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/highlight.min.js
        // var rgxp = new RegExp(word, 'gi');
        //var repl = '<code>' + word + '</code>';
        term = term.replace(/(\s+)/, "(<[^>]+>)*$1(<[^>]+>)*");
        var pattern = new RegExp("(" + term + ")", "gi");

        src_str = src_str.replace(pattern, "<mark>$1</mark>");
        src_str = src_str.replace(/(<mark>[^<>]*)((<[^>]+>)+)([^<>]*<\/mark>)/, "$1</mark>$2<mark>$4");

        //element = element.replace(rgxp, repl);
        return src_str;
    }
</script>
@endpush
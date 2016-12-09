@extends('_layouts/dark')
@section('content')
    {!!seo_breadcrumb_list('EzNewLife',$name,"/tag/".$name)!!}

    <h1 itemprop="name" class="hidden">{{$page['sub_title']}}</h1>

    <div class="row center-block article-category-main  hover11 column">

        @foreach ($tag_articles as $k=> $article)

            <div class="row center-block article-category-row" onclick="window.location='/{{$article->ez_map[0]->unique_id}}'" itemscope itemtype="https://schema.org/ImageObject">


                <div class="col-md-6 col-sm-6 col-lg-6 article-category-img ">


                    <a href="/{{$article->ez_map[0]->unique_id}}">
                        @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                            <img src="{!!("/focus_photos/400/".$article->photo) !!}?lastmod={!!date("YmdH")!!}"  itemprop="contentUrl"
                                 class="img-responsive img-thumbnail center-block" alt="{{$article->title}}">
                        @else
                            <img src="{!!("/images/nophoto.png") !!}"  itemprop="contentUrl"
                                 class="img-responsive img-thumbnail center-block" alt="{{$article->title}}">
                        @endif
                    </a>

                </div>
                @if(substr($article->publish_at,0,10)!='0000-00-00') <span itemprop="datePublished" class="hidden" content="{!!substr($article->publish_at,0,10)!!}"> {!!substr($article->publish_at,0,10)!!}</span> @endif
                <span itemprop="author" class="hidden">{!!$article->author->name!!}</span>
                <div class="col-md-6 col-sm-6 col-lg-6">

                    <div class="article-category-text">

                        <div class="head ">
                            <h2 itemprop="name"><a
                                        href="/{{$article->ez_map[0]->unique_id}}">
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
                                    </strong></a></h2>
                            @if ($article->tags->pluck('name')->count()>0)
                                <div class="row">

                                    <div class="col-xs-12 tag_hidden">

                                        @foreach ($article->tags->pluck('name')->all() as $key =>$tag_name)
                                            <a href="/tag/{{$tag_name}}"
                                               class="btn btn-xs bg-orange btn-flat">{!!$tag_name!!}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                        <HR/>
                        <div class="body rand-content">
                            <a href="/{{$article->ez_map[0]->unique_id}}" itemprop="description">

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
    <div class="text-center mar-top15">
        {!!show_page_bar_scroll($tag_articles,'btn-default btn-lg bg-black')!!}

    </div>

@endsection
@section('right_content')
    @include('_layouts/dark/right')
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
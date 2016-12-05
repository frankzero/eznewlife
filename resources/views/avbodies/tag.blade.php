@extends('_layouts/dark')
@section('content')


    <div class="row center-block article-category-main  hover11 column">

        @foreach ($tag_articles as $k=> $article)

            <div class="row center-block article-category-row" onclick="window.location='{{route('darks.show', ['id'=>$article->ez_map[0]->unique_id])}}'">
                <div class="col-md-6 col-sm-6 col-lg-6 article-category-img ">


                    <a href="{{route('darks.show', ['id'=>$article->ez_map[0]->unique_id])}}">
                        @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                            <img src="{!!("/focus_photos/400/".$article->photo) !!}?lastmod={!!date("YmdH")!!}"
                                 class="img-responsive img-thumbnail center-block" alt="{{$article->title}}">
                        @else
                            <img src="{!!("/images/nophoto.png") !!}"
                                 class="img-responsive img-thumbnail center-block" alt="{{$article->title}}">
                        @endif
                    </a>

                </div>

                <div class="col-md-6 col-sm-6 col-lg-6">

                    <div class="article-category-text">

                        <div class="head ">
                            <h4><a
                                        href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id])}}">
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
                            @if ($article->tags->pluck('name')->count()>0)
                                <div class="row">

                                    <div class="col-xs-12 tag_hidden">

                                        @foreach ($article->tags->pluck('name')->all() as $key =>$tag_name)
                                            <a href="{{route('darks.tag',['name'=>$tag_name])}}"
                                               class="btn btn-xs bg-orange btn-flat">{!!$tag_name!!}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                        <HR/>
                        <div class="body rand-content">
                            <a href="{{route('darks.show', ['id'=>$article->ez_map[0]->unique_id])}}">

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
    <div class="text-center">
        {!!$tag_articles->appends(['q' => $q])->render()!!}
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
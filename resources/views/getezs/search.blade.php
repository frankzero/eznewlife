@extends('_layouts/getez')
@section('content')

    <div class="alert alert-success " style="margin-bottom: 20px">字串「{{$q}}」搜尋結果</div>

        <div class="row center-block article-category-main  hover11 column">
            @if ($articles->count()==0) <div class="alert alert-default" >查無「{{$q}}」相關文章 </div>@endif
            @foreach ($articles as $k=> $article)

                <div class="row center-block article-category-row" onclick="window.location='{{route('getezs.show', ['id'=>$article->ez_map[0]->unique_id])}}'"   itemscope itemtype="http://schema.org/ImageObject">
                    <div class="col-md-6 col-sm-6 col-lg-6 article-category-img ">


                        <a href="{{route('getezs.show', ['id'=>$article->ez_map[0]->unique_id])}}">
                            @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                                <img src="{!!("/focus_photos/400/".$article->photo) !!}?lastmod={!!date("YmdH")!!}"    itemprop="contentUrl"
                                     class="img-responsive img-thumbnail center-block" alt="{{$article->title}}">
                            @else
                                <img src="{!!("/images/nophoto.png") !!}"   itemprop="contentUrl"
                                     class="img-responsive img-thumbnail center-block" alt="{{$article->title}}">
                            @endif
                        </a>

                    </div>

                    <div class="col-md-6 col-sm-6 col-lg-6">

                        <div class="article-category-text">

                            <div class="head ">
                                <h2 itemprop="name"><a
                                            href="{{route('getezs.show', ['id'=>$article->ez_map[0]->unique_id])}}">
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
                                        </strong></a></h2>
                            </div>
                            <div class=""><label class="btn btn-flat bg-orange btn-xs"><i class="fa fa-calendar"></i>
                                        @if(substr($article->publish_at,0,10)!='0000-00-00') <span itemprop="datePublished">{!!substr($article->publish_at,0,10)!!} </span>@endif </label>
                                    <label class="btn btn-flat bg-orange btn-xs"> <i class="fa fa-user"></i><span itemprop="author"> {!!$article->author->name!!}</span></label>
                            </div>

                            <HR/>
                            <div class="body rand-content">
                                <a href="{{route('getezs.show', ['id'=>$article->ez_map[0]->unique_id])}}" itemprop="description">

                                    @if ((strlen(strip_tags($article->summary))-mb_strlen(strip_tags($article->summary))<10))
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
                                </a></div>
                        </div>

                    </div>
                </div>
                @if ($k<9)

                @endif
            @endforeach

        </div>
        <div class="text-center mar-top15">
            {!!show_page_bar_scroll($articles->appends(['q' => $q]),'btn-danger btn-lg ')!!}
        </div>

@endsection
@section('right_content')
@include('_layouts.getez.right')
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
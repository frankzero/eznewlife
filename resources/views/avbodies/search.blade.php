@extends('_layouts/avbody')
@section('content')
    <div class="col-lg-9" id="main-comic">
        <div class="alert bg-maroon-active" style="margin-bottom: 20px">字串「{{$q}}」搜尋結果</div>

        @if ($articles->count()==0) <div class="alert alert-default" >查無「{{$q}}」相關動漫 </div>@endif



        <div class="row center-block">
            @if ($mobile==true)
                <div class="alert  adv"
                     role="alert">

                    <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_1"
                         class="  adv  text-center" style="min-width:100px;min-height:50px;margin:auto;padding:0;" role="alert">
                        @if(isset($_GET['ad'])) 手機版logo下方 : 1 @endif
                        {!! get_adCode(1, $plan, __DOMAIN__) !!}
                    </div>

                </div>
            @endif
            @if ($mobile==false)
                <div class="row">

                    <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_2 "
                         class="adv ">
                        @if(isset($_GET['ad'])) 電腦版logo下方 :2 @endif
                        {!! get_adCode(2, $plan, __DOMAIN__) !!}<br></div>
                </div>
            @endif

            @foreach ($articles as $k=> $article)
                <div class=" col-lg-4 col-md-4 col-sm-6 col-xs-12 img-item"   itemscope itemtype="https://schema.org/ImageObject"
                     onclick="window.location='/{{$article->ez_map[0]->unique_id}}'">

                    <div class="hovereffect" >
                        <a href="/{{$article->ez_map[0]->unique_id}}">
                            @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                                <img src="{!!("/focus_photos/400/".$article->photo) !!}?lastmod={!!date("YmdH")!!}"   itemprop="contentUrl"
                                     class="img-responsive img-thumbnail center-block" alt="{{$article->title}}">
                            @else
                                <img src="{!!("/images/nophoto.png") !!}"   itemprop="contentUrl"
                                     class="img-responsive img-thumbnail center-block" alt="{{$article->title}}">
                            @endif
                        </a>
                        <div class="overlay">
                            <h2 itemprop="name"> <a href="/{{$article->ez_map[0]->unique_id}}">
                                    @if ((strlen(strip_tags($article->title))-mb_strlen(strip_tags($article->title))<5))
                                        {{---english---}}
                                        @if (strlen(strip_tags($article->title))<70)
                                            {!!str_replace($q,'<span class="text-mark">'.$q.'</span>',strip_tags($article->title))!!}
                                        @else
                                            {!!str_replace($q,'<span class="text-mark">'.$q.'</span>',substr(strip_tags($article->title),0,69))!!}...
                                        @endif

                                    @else
                                        {{---chinese---}}
                                        @if (mb_strlen(strip_tags($article->title))<35)
                                            {!!str_replace($q,'<span class="text-mark">'.$q.'</span>',strip_tags($article->title))!!}
                                        @else
                                            {!!str_replace($q,'<span class="text-mark">'.$q.'</span>',mb_substr(strip_tags($article->title),0,34))!!}...
                                        @endif
                                    @endif
                                </a></h2>
                        </div>
                    </div>

                </div>

            @endforeach

        </div>
        @if ($mobile==true)
            <div class="alert  adv"
                 role="alert">

                <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_3"
                     class="  adv  text-center" style="min-width:100px;min-height:50px;margin:auto;padding:0;" role="alert">
                    @if(isset($_GET['ad'])) 電腦版分頁上方 :3 @endif
                    {!! get_adCode(3, $plan, __DOMAIN__) !!}
                </div>

            </div>
        @endif
        @if ($mobile==false)
            <div class="row">

                <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_4 "
                     class="adv ">
                    @if(isset($_GET['ad'])) 電腦版分頁上方 :4 @endif
                    {!! get_adCode(4, $plan, __DOMAIN__) !!}<br></div>
            </div>
        @endif
        <div class="text-center mar-top15" >

            {!!show_page_bar_scroll($articles->appends(['q' => $q]),'btn-default btn-lg bg-black')!!}
        </div>
    </div>




    <aside class="col-lg-3 " id="right-comic">
        @if ($mobile==false)
            @include('_layouts/avbody/search')
        @endif
        @include('_layouts/avbody/right')
    </aside>
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
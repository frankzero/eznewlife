

@extends('_layouts/enl')
@section('content')
    <div class="col-md-8 blog-main blog-main-show">
        <article class="blog-post article-main">



            <div class="body">
                <h1>{{ $article->title }}</h1>

                <div class="meta">
                    <div class="row">
                        <div class="col-lg-6" >
                        <i class="fa  fa-bookmark"></i>  {{$page['title']}}
                                @if(substr($article->publish_at,0,10)!='0000-00-00')<i class="fa fa-calendar"></i>{{substr($article->publish_at,0,10)}}@endif &nbsp;

                                </div>
                        <div class="col-lg-2" >
                        <script src="https://apis.google.com/js/platform.js" async defer></script>
                        <g:plusone></g:plusone></div>
                        <div class="fb-like" data-layout="button_count" data-href="{!!($page['share_url'])!!}"  ></div>
                    </div>
                    @if ($article->tags->pluck('name')->count()>0)
                        <div class="row">
                            <div class="col-lg-12" >
                            <i class="fa  fa-tag"></i>
                                @foreach ($article->tags->pluck('name')->all() as $key =>$tag_name)
                                    <a href="{{route('articles.tag',['name'=>$tag_name])}}" class="btn btn-xs btn-default">{!!$tag_name!!}</a>
                                @endforeach
                             </div>
                        </div>
                    @endif
                    <div class="row share" id="share" style="margin-top: 15px">
                        <div class="col-xs-3">



                            <a class="btn bg-light-blue btn-block"
                               href="http://www.facebook.com/sharer/sharer.php?u={!!rawurldecode($page['share_url'])!!}&description={!!rawurldecode($page['sub_title'])!!}"
                               target="_blank" alt="Facebook">
                                <i class="fa fa-facebook " id="fb_share"></i> <span class="share_btn">分享FB</span>
                            </a>
                        </div>
                        <div class="col-xs-3">
                            <a class="btn bg-red btn-block"
                               href="https://plus.google.com/share?url={!!rawurldecode($page['share_url'])!!}&t={!!rawurldecode($page['sub_title'])!!}"
                                                       target="_blank">
                                <i class="fa fa-google-plus"></i><span class="share_btn">分享 G+</span></a>
                        </div>
                        <div class="col-xs-3">
                            <a class="btn bg-aqua btn-block"
                               href="https://twitter.com/intent/tweet?url={!!rawurldecode($page['share_url'])!!}&text={!!rawurldecode($page['sub_title'])!!}"
                               target="_blank">
                                <i class="fa fa-twitter"></i><span class="share_btn">分享 Twitter</span></a>
                        </div>
                        <div class="col-xs-3">
                            <a class="btn btn-block" style="background-color: rgb(210,87,47) ;color:white "
                               href="http://www.plurk.com/?qualifier=shares&status={!!rawurldecode($page['share_url'])!!}"
                               target="_blank">
                                <strong style="font-family: Noto Sans CJK JP Black">P</strong> <span class="share_btn">&nbsp;分享 Plurk</span></a>
                        </div>
                    </div>

                </div>
                <div class="ad-md">
                    <div class="row">
                        <div title="1" id="ad_block_1" style="" role="alert">
                            {!! get_adCode(1, $plan, __DOMAIN__) !!}
                        </div>
                    </div>

                    <div class="article-content">
                {!!nl2br($article->content)!!}
            </div>

                    <div class="row">
                        <div class=" col-lg-3 text-right  ">
                            @if ($prev_link)
                                <a href="{{$prev_link}}" class="btn btn-page"  title="{{$prev_title}}" >上一頁</a>
                                <blockquote class="page-text row">
                                    <a href="{{$prev_link}}" class=""  title="{{$prev_title}}" > {{$prev_title}}</a>
                                </blockquote>
                            @endif
                        </div>

                        <div title="2" id="ad_block_2" class=" col-lg-6    text-center" style="padding:0;" role="alert">
                            {!! get_adCode(2, $plan, __DOMAIN__) !!}
                        </div>

                        <div class=" col-lg-3 text-left ">
                            @if ($next_link)
                                <a href="{{$next_link}}" class="btn btn-page"  title="{{$next_title}}" >下一頁</a>
                                <blockquote class="page-text row">
                                    <a href="{{$next_link}}" class=""  title="{{$next_title}}" > {{$next_title}}</a>
                                </blockquote>
                            @endif
                        </div>

        </article>



        <aside class="comments" id="comments">

            <article class="fb-comments" data-href="{{$page['share_url']}}" data-numposts="5"
                     data-width="100%">
            </article>

        </aside>
        <div class="row">
            <div title="3" id="ad_block_3" class="   text-center" style="min-width:100px;min-height:50px;margin:auto" role="alert">
                {!! get_adCode(3, $plan, __DOMAIN__) !!}
            </div>
        </div>
        <article class="ad-lg">
            <div class=" list-group">


                @for ($i = 5; $i < 10; $i++)
                    <a class="list-group-item  " href="{{route('articles.show', ['id'=>$rand_articles[$i]->ez_map[0]->unique_id,'title'=>hyphenize($rand_articles[$i]->title)])}}" style="height: 25%">
                        <div class="row">
                            <div class=" col-lg-2 ">

                                    @if (File::exists( public_path() . '/focus_photos'."/100/".$rand_articles[$i]->photo) and !empty($rand_articles[$i]->photo))
                                        <img  src="{!!("/focus_photos/100/".$rand_articles[$i]->photo) !!}?lastmod={!!$article->updated_at!!}" class="img-responsive img-thumbnail img-small" alt="{{$rand_articles[$i]->title}}">
                                    @else
                                        <img src="{!!("/images/nophoto.png") !!}"   class="img-responsive img-thumbnail"  alt="{{$article->title}}">
                                    @endif

                            </div>
                            <div class=" col-lg-10">

                                <h4 class="list-group-item-heading" >
                                    @if ((strlen(strip_tags($rand_articles[$i]->title))-mb_strlen(strip_tags($rand_articles[$i]->title))<10))
                                        {{---english--}}
                                        @if (strlen(strip_tags($rand_articles[$i]->title))<66)
                                            {!!strip_tags($rand_articles[$i]->title)!!}
                                        @else
                                            {!!substr(strip_tags($rand_articles[$i]->title),0,65)!!}...
                                            @endif

                                            @else

                                                    {{---chinese--}}
                                            @if (mb_strlen(strip_tags($rand_articles[$i]->title))<33)
                                                {!!strip_tags($rand_articles[$i]->title)!!}
                                            @else
                                                {!!mb_substr(strip_tags($rand_articles[$i]->title),0,32)!!}...
                                            @endif
                                        @endif
                                </h4>
                                <p class="list-group-item-text">
                                    @if ((strlen(strip_tags($rand_articles[$i]->summary))-mb_strlen(strip_tags($rand_articles[$i]->summary))<10))
                                        {{---english--}}
                                        @if (strlen(strip_tags($rand_articles[$i]->summary))<155)
                                            {!!strip_tags($rand_articles[$i]->summary)!!}
                                        @else
                                            {!!substr(strip_tags($rand_articles[$i]->summary),0,154)!!}...
                                            @endif

                                            @else

                                                    {{---chinese--}}
                                            @if (mb_strlen(strip_tags($rand_articles[$i]->summary))<81)
                                                {!!strip_tags($rand_articles[$i]->summary)!!}
                                            @else
                                                {!!mb_substr(strip_tags($rand_articles[$i]->summary),0,80)!!}...
                                            @endif
                                        @endif
                                </p>

                            </div>
                        </div>
                    </a>
                @endfor



            </div>


        </article>
    </div>


@endsection
@push('scripts')
<script>
document.getElementById('fb_share').onclick = function() {
FB.ui({
display: 'popup',
method: 'share',
href: '{{$page['share_url']}}',
}, function(response){});
}</script>
@endpush
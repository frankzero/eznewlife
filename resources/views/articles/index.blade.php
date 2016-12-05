@extends('_layouts/enl')
@section('content')
<style>

    .blog-main{margin-right:-10px !important;}
    </style>
    <h1 id="index" class="hidden">EzNewLife 最新文章列表2</h1>

    <div class="col-md-9 blog-main">
        <article class="blog-post">
            <div class="row center-block ">
                <div id="myCarousel" class="carousel slide col-md-8">

                    <div class="carousel-inner">
                        @foreach ($expert_articles as $k=> $article)
                            <div class="item carousel-img @if($k==0) active @endif">
                                <a href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)],false)}}">
                                @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                                    <img src="{!!("/focus_photos/".$article->photo) !!}?lastmod={!!date("YmdH")!!}" class="img-responsive img-thumbnail" alt="{{$article->title}}">
                                @else
                                    <img src="{!!("/images/nophoto.png") !!}" class="img-responsive img-thumbnail" alt="{{$article->title}}">
                                @endif
                                    </a>
                                <div class="carousel-caption">
                                    <h3><a href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)],false)}}">{{$article->title}}</a></h3>
                                </div>
                            </div>{{-- End Item --}}

                        @endforeach

                    </div>
                    {{-- Controls --}}
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="fa fa-caret-left btn-circle carousel-prev"></span></a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="fa fa-caret-right btn-circle carousel-next"></span></a>
                </div>

                <div class="list-group col-md-4 article-rand" style="height: 100%">

                    @for ($i = 5; $i < 10; $i++)
                        <a class="list-group-item list-group-item-black " href="{{route('articles.show', ['id'=>$rand_articles[$i]->ez_map[0]->unique_id,'title'=>hyphenize($rand_articles[$i]->title)],false)}}" style="height: 25%">
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
                                        @if (mb_strlen(strip_tags($rand_articles[$i]->title))<25 )
                                            {!!strip_tags($rand_articles[$i]->title)!!}
                                        @else
                                            {!!mb_substr(strip_tags($rand_articles[$i]->title),0,24)!!}...
                                        @endif
                                    @endif
                            </h4>
                        </a>
                    @endfor

                </div>
            </div>

        </article>
        <hr>

        <article class="blog-post">

            <div class="row center-block article-other-main" >
                @foreach ($other_articles as $k=> $article)
                    <div class="row center-block article-other-row" onclick="window.location='{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)],false)}}'" itemscope itemtype="http://schema.org/ImageObject">

                        <div class="col-md-6 col-sm-6 col-lg-6">


                            <a href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)],false)}}">
                                @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                                    <img  src="{!!("focus_photos/400/".$article->photo) !!}?lastmod={!!date("YmdH")!!}" itemprop="contentUrl"  class="img-responsive img-thumbnail img-middle" alt="{{$article->title}}">
                                @else
                                    <img src="{!!("images/nophoto.png") !!}"   class="img-responsive img-thumbnail"  itemprop="contentUrl"  alt="{{$article->title}}">
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
                                            </strong></a></h2>
                                    @if(substr($article->publish_at,0,10)!='0000-00-00') <span itemprop="datePublished" class="hidden" content="{!!substr($article->publish_at,0,10)!!}"> {!!substr($article->publish_at,0,10)!!}</span> @endif
                                    <span itemprop="author" class="hidden">{!!$article->author->name!!}</span>
                                    @if ($article->tags->pluck('name')->count()>0)
                                        <div class="row_tag">
                                            <div class="col-xs-1"> <i class="fa  fa-tag"></i></div>
                                            <div class="col-xs-10 tag_hidden" >

                                                @foreach ($article->tags->pluck('name')->all() as $key =>$tag_name)
                                                    <a href="{{route('articles.tag',['name'=>$tag_name],false)}}" class="btn btn-xs btn-default">{!!$tag_name!!}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                             {{-------}} <HR/>
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
                    @if ($k<3)

                    @endif
                @endforeach

            </div>
            <div id="loader-icon" class="overlay text-center body" style="margin-bottom: 30px">
                <i class="fa fa-refresh fa-spin"></i>
            </div>

            <input type="hidden" name="paged" id="paged" value="{{$paged}}">
            <input type="hidden" name="other_stop" id="other_stop" value="">


        </article>
    </div>




@endsection
@push('scripts')

<script>

var ajax_locked=false;

    $("#myCarousel").ready(function() {
        var boxheight = $('#myCarousel .carousel-inner').innerHeight();
        var itemlength = $('.article-rand .list-group-item').length;
        //console.log(boxheight+"-"+itemlength);
        var triggerheight = Math.floor (boxheight/itemlength+1);
        $('.list-group-item').outerHeight(triggerheight);
        //$('.carousel-img img').css("min-height",65*5+5);

    });



    $(document).ready(function(){

        function getresult(url) {
            $.ajax({
                url: url,
                type: "GET",
                data:  {paged:$("#paged").val()},
                beforeSend: function(){
                    $('#loader-icon').show();
                },
                complete: function(){
                    $('#loader-icon').hide();
                },
                success: function(data){
                    ajax_locked=false;
                    $(".article-other-main").append(data);
                    window.history.pushState({paged:$("#paged").val()}, $("#paged").val(), '/?paged='+$("#paged").val());
                },
                error: function(){}
            });
        }
        $(window).scroll(function(){
           var S= $(".blog-main").height() - $(window).height();
            var t= $(document).height() - $(window).height();
            // console.log("動態高度="+$(window).scrollTop()+'文件高='+$(document).height() +"windows 高="+$(window).height()+"載入點="+ S);
            if(ajax_locked===true) return;
            if ($(window).scrollTop() >=S){
                // console.log('loading');
                

                if($("#other_stop").val()=='') {
                 // var pagenum = parseInt($(".pagenum:last").val()) + 1;
                    var paged=parseInt($("#paged").val())+1;
                    $("#paged").val (paged);
                    // console.log($("#paged").val());
                    ajax_locked=true;
                    getresult('{{route('articles.ajax',[],false )}}');
                }
            }
        });
    });

</script>
@endpush
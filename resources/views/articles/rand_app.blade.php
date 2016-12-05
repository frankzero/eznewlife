@extends('_layouts/light')
@section('content')

    <style>
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6, body {

            font-family: 'Source Sans Pro', sans-serif, "微軟正黑體", "微软雅黑", "Helvetica Neue", Helvetica, Arial, "メイリオ", "맑은 고딕";
        }

        .carousel-inner > .item > img,
        .carousel-inner > .item > a > img {
            width: 100%;

            margin: auto;
        }

        .carousel-caption {
            background-color: rgba(2, 1, 1, 0.80);
            margin-left: -20%;
            /* margin-right: -18%; */
            width: 110%;
            margin-bottom: -5%;
        }

        .carousel-caption a {
            color: white;
        }

        .carousel-indicators {
            position: absolute;
            bottom: 10px;
            left: 70%;
            z-index: 15;
            width: 70%;
            padding-left: 0;
            margin-left: -15%;
            text-align: center;
            list-style: none;
        }


            h3 {
                font-size: 14px;
            }
            h5 {
                display:none
            }


        .carousel-caption {
            padding-top: 0px;

        }
        #myCarousel{max-width: 350px;}




        .img-small{
            width: 300px !important;
            height: 250px !important;
            margin: auto;
        }










    </style>
    <div id="myCarousel" class="carousel slide center-block" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner " role="listbox">
            @foreach ($articles as $k=> $article)
                <div class="item @if ($k==0) active @endif"  onclick="window.open('{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)],false)}}')">
                    <a href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)])}}" target="_blank" >
                    @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                        <img src="{!!("/focus_photos/".$article->photo) !!}?lastmod={!!date("YmdH")!!}"
                             class="img-responsive img-thumbnail img-small center-block" alt="{{$article->title}}">
                    @else
                        <img src="{!!("/images/nophoto.png") !!}" class="img-responsive img-thumbnail center-block"
                             alt="{{$article->title}}">
                    @endif
                        </a>
                    <div class="carousel-caption">
                        <h3>
                            <a href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)],false)}}" target="_blank">
                                @if ((strlen(strip_tags($article->title))-mb_strlen(strip_tags($article->title))<10))
                                    <!---english--->
                                    @if (strlen(strip_tags($article->title))<43)
                                        {!!strip_tags($article->title)!!}
                                    @else
                                        {!!substr(strip_tags($article->title),0,42)!!}...
                                        @endif

                                        @else

                                                <!---chinese--->
                                        @if (mb_strlen(strip_tags($article->title))<20)
                                            {!!strip_tags($article->title)!!}
                                        @else
                                            {!!mb_substr(strip_tags($article->title),0,21)!!}...
                                        @endif
                                    @endif

                            </a>
                        </h3>
                        <h5>
                            <a href="{{route('articles.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)],false)}}" target="_blank">
                                @if ((strlen(strip_tags($article->summary))-mb_strlen(strip_tags($article->summary))<10))
                                    <!---english--->
                                    @if (strlen(strip_tags($article->summary))<155)
                                        {!!strip_tags($article->summary)!!}
                                    @else
                                        {!!substr(strip_tags($article->summary),0,154)!!}...
                                        @endif

                                        @else

                                                <!---chinese--->
                                        @if (mb_strlen(strip_tags($article->summary))<81)
                                            {!!strip_tags($article->summary)!!}
                                        @else
                                            {!!mb_substr(strip_tags($article->summary),0,80)!!}...
                                        @endif
                                    @endif</a>
                        </h5>
                    </div>
                </div>
            @endforeach

        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
@endsection
@push('scripts')



@endpush
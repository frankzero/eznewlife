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

        <!-- Wrapper for slides -->
        <div class="carousel-inner " role="listbox">
            @foreach ($articles as $k=> $article)
                <div class="item @if ($k==0) active @endif"  onclick="window.open('{{route('comics.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)])}}')">
                    <a href="{{route('comics.show', ['id'=>$article->ez_map[0]->unique_id,'title'=>hyphenize($article->title)])}}" target="_blank" >
                    @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                        <img src="{!!asset("focus_photos/".$article->photo) !!}?lastmod={!!$article->updated_at!!}"
                             class="img-responsive img-thumbnail img-small center-block" alt="{{$article->title}}">
                    @else
                        <img src="{!!asset("images/nophoto.png") !!}" class="img-responsive img-thumbnail center-block"
                             alt="{{$article->title}}">
                    @endif
                        </a>

                </div>
            @endforeach

        </div>


    </div>
@endsection
@push('scripts')
<script>
$('.carousel').carousel({
interval: 2500
})
</script>
@endpush
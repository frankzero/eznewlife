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

        @media (max-width: 480px) {
            h3 {
                font-size: 14px;
            }

            h5 {
                display: none
            }
        }

        /* Extra Small Devices, .visible-xs-* */
        @media (min-width: 767px) {
            .img-small {
                width: 100%;
                height: 600px !important;
                margin: auto;
            }
        }

        @media (min-width: 600px) and (max-width: 767px) {

            .img-small {
                width: 100%;
                height: 400px !important;
                margin: auto;
            }
        }

        /* Small Devices, .visible-sm-* */
        @media (min-width: 479px) and (max-width: 599px) {

            .img-small {
                width: 100%;
                height: 360px !important;
                margin: auto;
            }
        }

        /* Medium Devices, .visible-md-* */
        @media (min-width: 400px) and (max-width: 478px) {
            .img-small {
                width: 100%;
                height: 300px !important;
                margin: auto;
            }
        }

        @media (min-width: 300px) and (max-width: 400px) {
            .img-small {
                width: 100%;
                height: 225px !important;
                margin: auto;
            }
        }

        .img-animation{
            width: 250px;
            height: 250px;
            display: block;
            padding: 4px;
            margin-bottom: 20px;
        }
        .small-font{
            font-size: 8px;;
        }
        .img-thumbnail-div{
            padding: 4px;
            margin-bottom: 20px;
            line-height: 1.42857143;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>

    <div class="container">
        <div class="row text-center ">
            <div class="col-lg-12">
                {!! $animations ->render() !!}</div>
        </div>

        @foreach ($animations as $k=> $animation)

                    <div class="col-lg-3 col-md-6 col-xs-12 img-thumbnail-div">

                        @if (File::exists( public_path() . '/animation_photos'."/".$animation->photo) and !empty($animation->photo))
                            <a
                               href="{!!asset("animation_photos/".$animation->photo)!!}" class="alert" data-title="{{$animation->title}}" data-content="{{$animation->photo}}" ><img
                                        src="{!!asset("animation_photos/".$animation->photo) !!}?lastmod={!!$animation->updated_at!!}"
                                        class="img-animation  center-block " title="{{$animation->title}}"></a>
                        @else
                            <img src="{!!asset("images/nophoto.png") !!}" class="img-animation center-block"
                                 title="{{$animation->title}}">
                        @endif

                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-danger">#{{$animation->id}}</button>
                                </div><!-- /btn-group -->
                                <input type="text" class="form-control small-font" value="{{$animation->title}}">
                            </div>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-success">ENL</button>
                                </div><!-- /btn-group -->
                                <input type="text" class="form-control small-font" value="{{'http://eznewlife.com/animation_photos'."/".$animation->photo}}">
                            </div>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-primary small-font">GoGo</button>
                                </div><!-- /btn-group -->
                                <input type="text" class="form-control small-font" value="{{'http://gogo-test.com/animation_photos'."/".$animation->photo}}">
                            </div>

                    </div>


        @endforeach
        <div class="row text-center ">
            <div class="col-lg-12">
            {!! $animations ->render() !!}</div>
        </div>

    </div>
@endsection
@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<script>  $(function() {
        var demos = {};

        $(document).on("click", ".alert", function (e) {
            var link = $(this).attr("href");
           var title =  $(this).attr("data-title");
            var content=$(this).attr("data-content");
            e.preventDefault();

            //var type = $(this).data("bb");
            bootbox.alert({
                message: '<img src="'+link+'" class="center-block img-thumbnail" style="margin-bottom:10px">'
            +' <div class="input-group">'
                +'<div class="input-group-btn">'
            +'<button type="button" class="btn btn-success">ENL</button>'
            +'</div>'
            +'<input type="text" class="form-control small-font" value="http://eznewlife.com/animation_photos/"'+content+'">'
            + '</div>'
            + '<div class="input-group">'
            + '<div class="input-group-btn">'
            + '<button type="button" class="btn btn-primary small-font">GoGo</button>'
            + '</div>'
            + '<input type="text" class="form-control small-font" value="http://gogo-test.com/animation_photos"'+content+'">'
                        + '</div>',
                title: title
                    });
        });
    });

</script>
@endpush
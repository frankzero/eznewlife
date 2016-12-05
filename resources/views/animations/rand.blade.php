@extends('_layouts/light')


@section('content')
    <link href="{{("/css/AdminLTE.css")}}" rel="stylesheet">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{('/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{('/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{('/bower_components/Ionicons/css/ionicons.min.css')}}">    <link rel="stylesheet" href="{{('/css/animations.css')}}">
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

        .img-animation {
            width: 250px;
            height: 250px;
            display: block;
            padding: 4px;
            margin-bottom: 20px;
        }

        .small-font {
            font-size: 8px;;
        }

        .img-thumbnail-div {
            padding: 4px;
            margin-bottom: 20px;
            line-height: 1.42857143;
            background-color: #fff;
            box-shadow: 0 0.125rem 0.313rem rgba( 0, 0, 0, .3 );

            min-height: 401px;
            margin-left:15px;

        }

        .tooltip-inner {
            max-width: 80px;
            /* If max-width does not work, try using width instead */
            width: 80px;
        }

        .video {
            height: 290px;
            border: 1px;
            text-align: center;
        }

        @media (min-width: 1200px) {
            .container {
                width: 1860px;
            }

            .col-lg-3 {
                width: 19%;
            }
        }

        .input-xs {
            height: 22px;
            padding: 2px 5px;
            font-size: 12px;
            line-height: 1.5;
        }
        .form-group{
            margin-bottom: 0px;;
        }
    </style>

    <div class="container">
        <div class="row text-center ">
            <div class="col-lg-12">
                {!! $animations ->appends(['user' => Input::get('user')])->render() !!}
            {!!Form::open(['route'=>['animations.rand'],'method'=>'get','id' => 'animation_search','class'=>"styled-select"])!!}

            {!!Form::select('user',$users,Input::get('user',''),['id' => 'uid'])!!}
            {!! Form::close() !!}
        </div>
            </div>

        @foreach ($animations as $k=> $animation)

            <div class="col-lg-3 col-md-6 col-xs-12 img-thumbnail-div">
                @if (File::exists( public_path() . '/animation_photos'."/".$animation->photo) and !empty($animation->photo) and  (strpos($animation->photo,"mp4")))
                    <div class="video">
                        <video id="my_video" width="250" height="250" preload="auto" autoplay="autoplay" muted="muted"
                               loop="loop" webkit-playsinline controls>
                            <source src="{!!("/animation_photos/".$animation->photo) !!}?lastmod={!!$animation->updated_at!!}"
                                    type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @elseif (File::exists( public_path() . '/animation_photos'."/".$animation->photo) and !empty($animation->photo))
                    <a
                            href="#" class="alert"
                            data-width="{{$animation->photo_width}}" data-height="{{$animation->photo_height}}"
                            data-size="{{round(($animation->photo_size/1024),2)}}MB"
                            data-title="{{$animation->title}}" data-content="{{$animation->photo}}"
                            data-id="{{$animation->id}}"
                            data-color="@if(round(($animation->photo_size/1024),2)>8) text-danger @endif" >

                        <img
                                src="{!!("/animation_photos/".$animation->photo) !!}?lastmod={!!$animation->updated_at!!}"
                                class="img-animation  center-block " title="{{$animation->title}}"></a>
                @else
                    <img src="{!!("/images/nophoto.png") !!}" class="img-animation center-block"
                         title="{{$animation->title}}">
                @endif

                @if (File::exists( public_path() . '/animation_photos'."/".$animation->photo) and !empty($animation->photo))
                    <ol class="breadcrumb inline center-block" style="margin-left:20%">
                        <li @if(round(($animation->photo_size/1024),2)>8) class="text-danger" @endif >{{round(($animation->photo_size/1024),2) ."MB"}}</li>
                        <li> {{$animation->photo_width."x".$animation->photo_height}}</li>
                        <li class="@if (File::exists( public_path() . '/animation_photos'."/".$animation->photo) and !empty($animation->photo) and  (strpos($animation->photo,"mp4")))text-danger @else text-success @endif"><strong>{{strtoupper(File::extension($animation->photo))}}</strong></li>

                    </ol>
                @endif
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="txt_{{$animation->id}}" class="col-lg-2 control-label">#{{$animation->id}}</label>
                        <div class="col-lg-10">
                            <button type="button" class="btn btn-default btn-xs" data-toggle="tooltip"
                                    data-placement="top" title="點擊可複製#{{$animation->id}}"
                                    data-clipboard-target="#txt_{{$animation->id}}"><i class="fa fa-copy"></i></button>
                            <!-- /btn-group -->
                            <input type="text" class="col-lg-8 input-xs" id="txt_{{$animation->id}}"
                                   value="{{$animation->title}}">{{$animation->name}}

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="txt_{{$animation->id}}" class="col-lg-2 control-label">URL</label>
                        <div class="col-lg-10">
                            <button type="button" class="btn btn-default btn-xs" data-toggle="tooltip"
                                    data-placement="top" title="點擊可複製URL"
                                    data-clipboard-target="#url_{{$animation->id}}"><i class="fa fa-copy"></i></button>
                            <!-- /btn-group -->
                            <input type="text" class="col-lg-8 input-xs" id="url_{{$animation->id}}"
                                   value="http://getez.info/animations/{{$animation->id}}">

                            <a class="btn btn-default btn-xs "
                               href="{!!"https://developers.facebook.com/tools/debug/og/object/?q=".rawurlencode('http://getez.info/animations/'.$animation->id)!!}"
                               target="_{{$animation->id}}"> &nbsp;<i class="fa  fa-bug"></i></a>
                            <a class="btn btn-default btn-xs"
                               href="{!!"https://www.facebook.com/sharer/sharer.php?u=".rawurlencode('http://getez.info/animations/'.$animation->id)!!}"
                               target="_{{$animation->id}}"> &nbsp;<i class="fa  fa-facebook"></i></a>

                        </div>
                    </div>
                    @if  (strpos($animation->photo,"mp4"))
                        {{--*/ $site_url=str_replace("http","https",Config::get('app.master_url')) ;
                        $site_name="ENL";
                        /*--}}
                        <div class="form-group">

                            <label for="txt_{{$site_name}}_{{$animation->id}}"
                                   class="col-lg-2 control-label">{{$site_name}}</label>
                            <div class="col-lg-10">
                                <button type="button" class="btn btn-default btn-xs " data-toggle="tooltip"
                                        data-placement="top" title="點擊可複製{{$site_name}} "
                                        id="{{$site_name}}_{{$animation->id}}"
                                        data-clipboard-target="#txt_{{$site_name}}_{{$animation->id}}"><i
                                            class="fa fa-copy"></i></button>
                                <a class="btn btn-default btn-xs "
                                   href="{!!"https://developers.facebook.com/tools/debug/og/object/?q=".rawurlencode($site_url.'/animations'."/".$animation->id)!!}"
                                   target="_{{$animation->id}}"> &nbsp;<i class="fa  fa-bug"></i></a>
                                <a class="btn btn-default btn-xs"
                                   href="{!!"https://www.facebook.com/sharer/sharer.php?u=".rawurlencode($site_url.'/animations'."/".$animation->id)!!}"
                                   target="_{{$animation->id}}"> &nbsp;<i class="fa  fa-facebook"></i></a>
                                <input type="text" class="col-lg-8 input-xs " id="txt_{{$site_name}}_{!!$animation->id!!}"
                                       value="{{$site_url.'/animations'."/".$animation->id}}">

                            </div>

                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default btn-xs " data-toggle="tooltip"
                                    data-placement="top" title="複製mp4網址{{$site_name}} "
                                    id="{{$site_name}}_{{$animation->id}}"
                                    data-clipboard-target="#mp4_{{$site_name}}_{{$animation->id}}"><i
                                        class="fa fa-copy"></i></button>
                            <label for="mp4_{{$site_name}}_{{$animation->id}}"
                                   class="col-lg-2 control-label">MP4</label>
                            <input type="text" class="col-lg-8 input-xs " id="mp4_{{$site_name}}_{{$animation->id}}"
                                   value="{{$site_url.'/animation_photos'."/".$animation->photo}}">
                        </div>
                   @else

                 @foreach (Config::get('app.animation_url') as $site_name =>$site_url)
                        @if  (strpos($animation->photo,"mp4")){{--*/ $site_url=str_replace("http","https",$site_url) /*--}} @endif

                        <div class="form-group">

                            <label for="txt_{{$site_name}}_{{$animation->id}}"
                                   class="col-lg-2 control-label">{{$site_name}}</label>

                            <div class="col-lg-10">
                                <button type="button" class="btn btn-default btn-xs " data-toggle="tooltip"
                                        data-placement="top" title="點擊可複製{{$site_name}} "
                                        id="{{$site_name}}_{{$animation->id}}"
                                        data-clipboard-target="#txt_{{$site_name}}_{{$animation->id}}"><i
                                            class="fa fa-copy"></i></button>

                                <a class="btn btn-default btn-xs "
                                   href="{!!"https://developers.facebook.com/tools/debug/og/object/?q=".rawurlencode($site_url.'/animation_photos'."/".$animation->photo)!!}"
                                   target="_{{$animation->id}}"> &nbsp;<i class="fa  fa-bug"></i></a>
                                <a class="btn btn-default btn-xs"
                                   href="{!!"https://www.facebook.com/sharer/sharer.php?u=".rawurlencode($site_url.'/animation_photos'."/".$animation->photo)!!}"
                                   target="_{{$animation->id}}"> &nbsp;<i class="fa  fa-facebook"></i></a>
                                <input type="text" class="col-lg-8 input-xs " id="txt_{{$site_name}}_{{$animation->id}}"
                                       value="{{$site_url.'/animation_photos'."/".$animation->photo}}">
                            </div>
                        </div>

                    @endforeach
                    @endif
                </form>
            </div>



        @endforeach
        <div class="row text-center ">
            <div class="col-lg-12">
                {!! $animations ->appends(['user' => Input::get('user')])->render() !!}
            </div>
        </div>

    </div>
@endsection
@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<script src="https://cdn.jsdelivr.net/clipboard.js/1.5.5/clipboard.min.js"></script>
<script>
    jQuery(function () {
        // remove the below comment in case you need chnage on document ready
        // location.href=jQuery("#selectbox").val();
        jQuery("#uid").change(function () {
            location.href ="?user="+jQuery(this).val();
        })
    })
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    clipboardDemos = new Clipboard('.btn');
    clipboardDemos.on('success', function (e) {
        e.clearSelection();
        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);
        // $(e.trigger).tooltip('show');
        $(e.trigger).attr('title', '已複製!!').tooltip('fixTitle').tooltip('show');
        $(e.trigger).attr('title', '點擊可複製').tooltip('fixTitle');
        // var tmp=e.trigger;
        //  console.log(tmp.indexOf("id="));
    });
    $(function () {
        var demos = {};

        $(document).on("click", ".noalert", function (e) {
            var link = $(this).attr("href");
            var title = $(this).attr("data-title");
            var content = $(this).attr("data-content");
            console.log(content);
            e.preventDefault();

            bootbox.alert({
                message: '<img src="' + link + '" class="center-block img-thumbnail" style="margin-bottom:10px">'
                + ' <ol class="breadcrumb inline" style="margin-left:35%">'
                + '<li class=' + $(this).attr("data-color") + '>' + $(this).attr("data-size") + '</li>'
                + '<li>' + $(this).attr("data-width") + "x" + $(this).attr("data-height") + '</li>'
                + '<li>' + content.split('.').pop().toUpperCase() + '</li>'
                + '</ol>'
                @foreach (Config::get('app.animation_url') as $site_name =>$site_url)

                + ' <div class="input-group">'
                + '<div class="input-group-btn">'
                + '<button type="button" class="btn btn-success bg-olive " title="點擊可複製"  data-placement="top" id="m_{{$site_name}}_' + $(this).attr("data-id") + '"  data-clipboard-target="#m_txt_{{$site_name}}_{{$animation->id}}">{{$site_name}}</button>'
                + '</div>'
                + '<input type="text" class="form-control small-font"  id="m_txt_{{$site_name}}_' + $(this).attr("data-id") + '" value="{{$site_url}}/animation_photos/' + content + '">'
                + '</div>'
                @endforeach
                ,

                title: title
            });
        });
    });

</script>
@endpush

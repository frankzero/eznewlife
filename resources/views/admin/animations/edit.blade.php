@extends('_layouts/admin')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{$page['sub_title']}}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    @if (count($errors) > 0)
                        <div class="row">
                            <div class="alert alert-danger  alert-dismissable col-lg-offset-1 col-lg-8">
                                <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                                <!--auto -save 訊息提示區塊--->
                        <div class="row  direct-chat direct-chat-success" style="display:none" id="auto-save-block">
                            <div class="direct-chat-msg  col-xs-offset-1 col-lg-5 ">
                                <!-- /.direct-chat-info -->
                                <img src="{{('/images/128.png')}}" class="direct-chat-img" alt="User Image">

                                <div class="direct-chat-text form-status-holder">

                                </div>
                                <!-- /.direct-chat-text -->
                            </div>
                        </div>
                        {!!Form::open(['route'=>['admin.animations.auto.update',$id],'method'=>'post','id' => 'animation_edit','class'=>"form-horizontal ",'files' => true])!!}

                        <input type="hidden" name="id" id="id" value="{{$id}}">
                        <input type="hidden" name="type" id="type" value="animations">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 col-lg-1 control-label">標題</label>
                            <div class="col-sm-10 col-lg-8">
                                {!!Form::text('title',(Input::old('title')) ? Input::old('title') : $title,array('class' => 'form-control','id'=>'title','placeholder'=>'請輸入動圖標題'))!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="file" class="col-sm-2 col-lg-1 control-label">動圖</label>

                            <div class="col-sm-5 col-lg-8">


                                    @if (!empty($animation_url))
                                    @if  (strpos($photo,"mp4"))
                                        {{--*/ $url=str_replace("http","https",Config::get('app.master_url')) ;
                                        $site="ENL";
                                        /*--}}
                                    <div id="animation_bar" class="form-group">
                                        <div class="col-lg-4">{{$site}}
                                            <a class=" copy btn btn-xs btn-default"  id="{{$site}}_btn" title="點擊可複製" data-toggle="tooltip" data-clipboard-target="#{{$site}}_url"><i class="fa fa-copy"></i> </a>
                                            <a class="btn btn-default btn-xs "
                                               href="{!!"https://developers.facebook.com/tools/debug/og/object/?q=".rawurlencode($url.'/animations'."/".$id)!!}"
                                               target="_{{$id}}" title="facebook debugger" data-toggle="tooltip"> &nbsp;<i class="fa  fa-bug"></i></a>
                                            <a class="btn btn-default btn-xs"
                                               href="{!!"https://www.facebook.com/sharer/sharer.php?u=".rawurlencode($url.'/animations'."/".$id)!!}"
                                               target="_{{$id}}" title="facebook share" data-toggle="tooltip"> &nbsp;<i class="fa  fa-facebook"></i></a>
                                        </div>
                                        <div class="col-lg-8"> {!!Form::text($site.'_url',$url.'/animations'."/".$id,array('class' => 'form-control','id'=>$site.'_url','name'=>$site.'_url'))!!}</div>
                                        <div class="col-lg-4">MP4
                                            <a class=" copy btn btn-xs btn-default"  id="mp4_btn" title="複製mp4網址" data-toggle="tooltip" data-clipboard-target="#mp4_url"><i class="fa fa-copy"></i> </a>

                                        </div>
                                        <div class="col-lg-8"> {!!Form::text('mp4_url',$url.'/animation_photos'."/".$photo,array('class' => 'form-control','id'=>'mp4_url','name'=>'mp4_url'))!!}</div>
                                        </div>

                                    @else
                                        @foreach ($animation_url as $site =>$url) <div id="animation_bar" class="form-group">
                                            @if  (strpos($photo,"mp4")){{--*/ $url=str_replace("http","https",$url) /*--}} @endif
                                                <div class="col-lg-4">{{$site}}
                                            <a class=" copy btn btn-xs btn-default"  id="{{$site}}_btn" title="點擊可複製" data-toggle="tooltip" data-clipboard-target="#{{$site}}_url"><i class="fa fa-copy"></i> </a>
                                                <a class="btn btn-default btn-xs "
                                                   href="{!!"https://developers.facebook.com/tools/debug/og/object/?q=".rawurlencode($url)!!}"
                                                   target="_{{$id}}" title="facebook debugger" data-toggle="tooltip"> &nbsp;<i class="fa  fa-bug"></i></a>
                                                <a class="btn btn-default btn-xs"
                                                   href="{!!"https://www.facebook.com/sharer/sharer.php?u=".rawurlencode($url)!!}"
                                                   target="_{{$id}}" title="facebook share" data-toggle="tooltip"> &nbsp;<i class="fa  fa-facebook"></i></a>
                                                    </div>
                                            <div class="col-lg-8"> {!!Form::text($site.'_url',$url,array('class' => 'form-control','id'=>$site.'_url','name'=>$site.'_url'))!!}


                                            </div></div>
                                        @endforeach
                                    @endif

                                    @endif

                                <input type='file' id="imgInp" name="photo" data-placement="right" data-toggle="tooltip"
                                       data-title="tip:請使用檔案大小小於 8MB 的 GIF 檔"/>
                                @if (File::exists( public_path() . '/animation_photos'."/".$photo) and !empty($photo) and  (strpos($photo,"mp4")))
                                 <video id="my_video" width="200" height="140" preload="auto" autoplay="autoplay" muted="muted" loop="loop" webkit-playsinline controls>
                                    <source  src="{!!("/animation_photos/".$photo) !!}?lastmod={!!$updated_at!!}" type="video/mp4" >
                                    Your browser does not support the video tag.
                                </video>
                                @endif

                                <img id="blah"
                                     src="@if (File::exists( public_path() . '/animation_photos'."/".$photo) and !empty($photo))
{!!("/animation_photos/".$photo) !!}?lastmod={!!$updated_at!!}@else {{("/images/no_image.png")}}
@endif" class="img-responsive img-thumbnail" alt="特色圖片" style="@if (File::exists( public_path() . '/animation_photos'."/".$photo) and !empty($photo) and  (strpos($photo,"mp4"))) display:none; @endif width:200px;height: 140px" data-toggle="tooltip"
                                     data-placement="right" data-title="tip:請使用檔案大小小於 8MB 的 GIF 檔"/>

                                @if (File::exists( public_path() . '/animation_photos'."/".$photo) and !empty($photo))
                                    &nbsp;<ol class="breadcrumb inline">
                                        <li @if(round(($photo_size/1024),2)>8) class="text-danger" @endif >{{round(($photo_size/1024),2) ."MB"}}</li>
                                        <li> {{$photo_width."x".$photo_height}}</li>
                                        <li>{{strtoupper(File::extension($photo))}}</li>
                                    </ol>
                                @endif
                            </div>



                        </div>

                        <div class="form-group  row " id="resize" style="display: none">
                            <label for="stand" class="col-sm-2 col-lg-1 control-label">縮放</label>
                            <div class="col-sm-6 col-lg-2">
                                {!!Form::text('stand',(Input::old('stand')) ? Input::old('stand') : 225,array('class' => 'form-control','id'=>'stand',
                                'placeholder'=>'縮放比','number'))!!}


                            </div>
                            <p class="help-block"><span class="fa  fa-lightbulb-o"></span> 當圖片>8MB或尺寸過小，圖片會進行縮放 </p>
                        </div>
                        <div class="box-footer"  >
                                                                               <button type="reset" class="btn btn-default">Cancel</button>
                                              <button type="submit" class="btn btn-primary pull-right">修改動圖</button>
                        </div>
                </div>
                <!-- /.box-footer -->
                {!! Form::close() !!}
            </div>

        </div>
        </div>
    </section>

@endsection
@push('scripts')
@include('admin.animations.edit_js')
@endpush

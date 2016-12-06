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
                                <img src="{{cdn('images/128.png')}}" class="direct-chat-img" alt="User Image">

                                <div class="direct-chat-text form-status-holder">

                                </div>
                                <!-- /.direct-chat-text -->
                            </div>
                        </div>
                        {!!Form::open(['route'=>['admin.animations.auto.update',$id],'method'=>'post','id' => 'animation_edit','class'=>"form-horizontal article_form",'files' => true])!!}

                        <input type="hidden" name="id" id="id" value="{{$id}}">
                        <input type="hidden" name="type" id="type" value="animations">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 col-lg-1 control-label">標題</label>

                            <div class="col-sm-10 col-lg-8">

                                {!!Form::text('title',(Input::old('title')) ? Input::old('title') : $title,array('class' => 'form-control','id'=>'title','placeholder'=>'請輸入文章主題','required'))!!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="file" class="col-sm-2 col-lg-1 control-label">動圖</label>

                            <div class="col-sm-5 col-lg-8">

                                <div id="animation_bar" class="form-group">
                                    @if (!empty($animation_url))
                                        @foreach ($animation_url as $site =>$url)
                                            <a class="col-sm-2 col-lg-1  copy btn btn-xs btn-success"  id="{{$site}}_btn" title="點擊可複製" data-toggle="tooltip" data-clipboard-target="#{{$site}}_url">{{$site}}</a>
                                             <div class="col-sm-10 col-lg-11"> {!!Form::text($site.'_url',$url,array('class' => 'form-control','id'=>$site.'_url','name'=>$site.'_url'))!!}</div>
                                        @endforeach
                                    @endif
                                </div>
                                <input type='file' id="imgInp" name="photo" data-placement="right" data-toggle="tooltip"
                                       data-title="tip:圖片寬至少大於600px，有助於facebook 分享"/>

                                <img id="blah"
                                     src="@if (File::exists( public_path() . '/animation_photos'."/".$photo) and !empty($photo))
{!!cdn("animation_photos/".$photo) !!}?lastmod={!!$updated_at!!}@else {{asset('images/no_image.png')}}
@endif" class="img-responsive img-thumbnail" alt="特色圖片" style="width:200px;height: 140px" data-toggle="tooltip"
                                     data-placement="right" data-title="tip:建議圖片尺寸 640X480 (4*3 比例)"/>
                            </div>
                        </div>

                        <div class="box-footer">
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

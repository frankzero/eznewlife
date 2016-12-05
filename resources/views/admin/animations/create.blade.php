@extends('_layouts/admin')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{$page['sub_title']}}</h3>
        </div><!-- /.box-header -->
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

                </div><!-- /.direct-chat-text -->
            </div>
         </div>
        {!!Form::open(['route'=>['admin.animations.store'],'method'=>'post','id' => 'animation_form','class'=>"form-horizontal ",'files' => true])!!}
            <input type="hidden" name="id" id="id" value="0">
            <input type="hidden" name="type" id="type" value="animations">
            <div class="box-body">

                <div class="form-group">
                    <label for="title" class="col-sm-2 col-lg-1 control-label">標題</label>
                    <div class="col-sm-12 col-lg-8">

                        {!!Form::text('title',(Input::old('title')) ? Input::old('title') : $title,array('class' => 'form-control','id'=>'title','placeholder'=>'請輸入動圖標題'))!!}
                    </div>
                </div>



                <div class="form-group">
                <label for="file" class="col-sm-2 col-lg-1 control-label">動圖上傳</label>
                    <div class="col-sm-12 col-lg-8">
							<div id="animation_bar" class="form-group"></div>
                        <input type='file' id="imgInp" name="photo" onclick=""/>

                        <img id="blah" src="{{("/images/no_image.png")}}"  class="img-responsive img-thumbnail" alt="特色圖片" style="width:200px;height: 140px" data-toggle="tooltip"  data-placement="right" data-title="tip:請使用檔案大小小於 8MB 的 GIF 檔"/>
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
                 <div class="box-footer">
                <button type="reset" class="btn btn-default">Cancel</button>
                 <button type="submit" class="btn btn-success pull-right">新增動圖</button>
            </div><!-- /.box-footer -->
                {!! Form::close() !!}
    </div>

</div>
    </div></section>

@endsection
@push('scripts')
@include('admin.animations.edit_js')
@endpush

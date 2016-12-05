@extends('_layouts/admin')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
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
                        {!!Form::open(['route'=>['admin.newsletters.store'],'method'=>'post','id' => 'article_form','class'=>"form-horizontal article_form",'files' => true])!!}
                        <input type="hidden" name="id" id="id" value="0">
                        <input type="hidden" name="type" id="type" value="newsletters">
                        <div class="box-body">

                            <div class="form-group">
                                <label for="title" class="col-sm-2 col-lg-1 control-label">主旨</label>
                                <div class="col-sm-10 col-lg-5">

                                    {!!Form::text('title',(Input::old('title')) ? Input::old('title') : $title,array('class' => 'form-control','id'=>'title','placeholder'=>'請輸入信件主題','required'))!!}
                                </div>
                            </div>



                            <div class="form-group sandbox-container">
                                <label for="content" class="col-sm-2 col-lg-1 control-label">信件內容</label>
                                <div class="col-sm-10 col-lg-10">
                                    {!!Form::textarea('content',(Input::old('content')) ? Input::old('content') : $content
                                 ,array('class' => 'input-block-level',  'rows'    => 18,'id'=>'summernote','maxlength'=>150,
                                 'placeholder'=>'請輸入信件內容'))!!}



                                </div>

                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" name="send" class="btn btn-primary pull-right loose"><i class="fa fa-send"></i> 立即寄送</button> &nbsp;&nbsp;
                                <button type="submit" name="edit" class="btn btn-success pull-right loose">修改電子報</button>
                            </div><!-- /.box-footer -->
                            {!! Form::close() !!}
                        </div>

                </div>
            </div>
    </section>

@endsection
@push('scripts')

@endpush

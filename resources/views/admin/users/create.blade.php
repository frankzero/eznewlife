@extends('_layouts/admin')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
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

                    {!!Form::open(['route'=>['admin.users.store'],'method'=>'post','id' => 'user_form','class'=>"form-horizontal",'files' => true])!!}


                    <div class="box-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-3 col-lg-2 control-label">帳號</label>

                            <div class="col-sm-9 col-lg-4 ">

                                {!!Form::text('name',(Input::old('name')) ? Input::old('name') : $name,array('class' => 'form-control','id'=>'name','placeholder'=>'請輸入您的帳號','required'))!!}
                            </div>
                        </div>

                        <div class="form-group sandbox-container">
                            <label for="password" class="col-sm-3 col-lg-2 control-label">密碼</label>

                            <div class="col-sm-9 col-lg-4">
                                <input type="password" class="form-control" name="password" id="password"
                                       placeholder="請輸入6個字元以上的密碼"
                                       class="form-control" id="password"  minlength="6">

                            </div>
                        </div>
                        <div class="form-group sandbox-container">
                            <label for="publish_date" class="col-sm-3 col-lg-2 control-label">郵件</label>

                            <div class="col-sm-9 col-lg-10">
                                {!!Form::text('email',(Input::old('email')) ? Input::old('email') : $email,array('class' => 'form-control','id'=>'email','placeholder'=>'xx@xx.xx','required'))!!}
                            </div>
                        </div>
                        <div class="form-group sandbox-container">
                            <label for="fb_page" class="col-sm-3 col-lg-2 control-label">@if(isset($fb_page) and !empty($fb_page)) <a href="https://www.facebook.com/{{$fb_page}}" target="_blank">@endif粉絲團@if(isset($fb_page) and !empty($fb_page)) </a> @endif
                            </label>

                            <div class="col-sm-9 col-lg-20">
                                {!!Form::text('fb_page',(Input::old('fb_page')) ? Input::old('fb_page') : $fb_page,array('class' => 'form-control','id'=>'fb_page','placeholder'=>'414353681947363','number'=>true))!!}
                            </div>
                        </div>

                        <div class="form-group sandbox-container">
                            <label for="role" class="col-sm-3 col-lg-2 control-label">身份</label>
                            <div class="col-sm-9 col-lg-20 ">
                                <label class="radio-inline">
                                {!! Form::radio('role', 'A',  Input::old('role', $role == 'A' ), array('id'=>'role', 'class'=>'radio')) !!} &nbsp; 管理員 &nbsp;
                                    </label>
                                <label class="radio-inline">
                                {!! Form::radio('role', 'U',Input::old('role', $role == 'U' ) , array('id'=>'role', 'class'=>'radio')) !!} &nbsp; 小編 &nbsp;
                               </label>
                           </div>
                        </div>
                        <div class="box-footer">
                            <button type="reset" class="btn btn-default">Cancel</button>
                            <button type="submit" class="btn btn-success pull-right">新增用戶</button>
                        </div>
                        <!-- /.box-footer -->
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
    </section>

@endsection
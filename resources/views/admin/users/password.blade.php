@extends('_layouts/admin')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="box box-warning">
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

                    {!!Form::open(['route'=>['admin.users.password.update'],'method'=>'post','id' => 'user_password','class'=>"form-horizontal",'files' => true])!!}
                    <input type="hidden" name="id" id="id" value="{{$id}}">
                    <input name="_method" type="hidden" value="PUT">

                    <div class="box-body">


                        <div class="form-group sandbox-container">
                            <label for="password" class="col-sm-3 col-lg-2 control-label">密碼</label>

                            <div class="col-sm-9 col-lg-4">
                                <input type="password" class="form-control" name="password" id="password"
                                       placeholder="請輸入舊密碼"
                                       class="form-control"   minlength="6">

                            </div>
                        </div>
                        <div class="form-group sandbox-container">
                            <label for="password" class="col-sm-3 col-lg-2 control-label">新密碼</label>

                            <div class="col-sm-9 col-lg-4">
                                <input type="password" class="form-control" name="new_password" id="new_password"
                                       placeholder="6個字元以上的新密碼"
                                       class="form-control"  minlength="6"  notEqualTo="#password">

                            </div>
                        </div>
                        <div class="form-group sandbox-container">
                            <label for="password" class="col-sm-3 col-lg-2 control-label">確認密碼</label>

                            <div class="col-sm-9 col-lg-4">
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                                       placeholder="請再一次輸入新密碼"
                                       class="form-control"  minlength="6" equalto="#new_password">

                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="reset" class="btn btn-default">Cancel</button>
                            <button type="submit" class="btn btn-success pull-right">修改密碼</button>
                        </div>
                        <!-- /.box-footer -->
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
    </section>

@endsection
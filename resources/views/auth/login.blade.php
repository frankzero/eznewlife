@extends('_layouts/guest')
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>EzNewlife</b></a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">小編創作平台</p>
            @if (count($errors) > 0)

                <div class="alert alert-danger alert-dismissable">
                        <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{(url('/auth/login'))}}" id="login_form">
                {!! csrf_field() !!}
                <input type="hidden"   name="login_url" id="login_url" value="{{("//")}}" required="true">
                <div class="form-group has-feedback">

                    <input type="text" class="form-control" placeholder="帳號"  id="name" name="name" value="{{ old('name') }}" required="true">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" id="password" placeholder="密碼" required="true"  minlength="6">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="remember"> 記住我
                            </label>
                        </div>
                    </div><!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat btn-success">登入</button>
                    </div><!-- /.col -->
                </div>
            </form>


            <a href="{{(url('/password/email'))}}">忘記密碼?</a><br>


        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->




@endsection







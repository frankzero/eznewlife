@extends('_layouts/guest')
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>奇創</b>數位科技</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">忘記密碼?</p>
            @if (count($errors) > 0)

                <div class="alert alert-danger alert-dismissable">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{(url('/password/email'))}}" id="forget_password_form">
                {!! csrf_field() !!}

                <div class="form-group has-feedback">

                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="請輸入您的E-Mail">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>

                <div class="form-group has-feedback">

                <button type="submit" class="btn btn-primary btn-block btn-flat btn-warning">
                        傳送重置密碼連結
                </button>
                </div>
            </form>
            <a href="{{(url('/auth/login'))}}" class="text-center">登入</a>
        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->




@endsection
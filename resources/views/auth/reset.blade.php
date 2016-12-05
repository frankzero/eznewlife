@extends('_layouts/guest')
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>奇創</b>數位科技</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">重置密碼</p>
            @if (count($errors) > 0)

                <div class="alert alert-danger alert-dismissable">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
<form method="POST" action="{{(url('/password/reset'))}}">
    {!! csrf_field() !!}
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group has-feedback">
        <input type="email" name="email" value="{{ old('email') }}" class="form-control">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>

    <div class="form-group has-feedback">
        <input type="password" name="password" placeholder="Retype password"  class="form-control">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Retype password" name="password_confirmation">
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
    </div>


    <div>
        <button type="submit" class="btn btn-primary btn-block btn-flat btn-warning">
            Reset Password
        </button>
    </div>
</form>
            <a href="{{(url('/auth/login'))}}" class="text-center">登入</a>
        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->




@endsection
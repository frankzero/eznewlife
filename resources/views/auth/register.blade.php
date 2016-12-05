@extends('_layouts/guest')
@section('content')
    <div class="register-box">
        <div class="register-logo">
            <a href="../../index2.html"><b>奇創</b>數位科技</a>
        </div>

        <div class="register-box-body">
            <p class="login-box-msg">註冊成新會員</p>
            @if (count($errors) > 0)

                <div class="alert alert-danger alert-dismissable">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{(url('/auth/register'))}}">
                {!! csrf_field() !!}
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="account" name="name" value="{{ old('name') }}">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" name="email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Retype password" name="password_confirmation">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">

                    </div><!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-success btn-block btn-flat">Register</button>
                    </div><!-- /.col -->
                </div>
            </form>



            <a href="{{(url('/auth/login'))}}" class="text-center">登入</a>
        </div><!-- /.form-box -->
    </div><!-- /.register-box -->



    @endsection
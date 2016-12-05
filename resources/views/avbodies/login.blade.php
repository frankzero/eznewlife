@extends('_layouts/avbody')
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{url("/")}}"><b></b></a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">親愛的使用者</p>
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

                <div class="row">


                  我們才能為您收藏最愛的漫畫。,收看本站漫畫，完全免費

                </div>
            </form>
            <div class="social-auth-links text-center">

                <a href="{{route('avbodies.facebook.login')}}" class="btn btn-block btn-social btn-facebook btn-lg"><i class="fa fa-facebook"></i>Facebook 註冊/登入</a>

            </div>



        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->




@endsection







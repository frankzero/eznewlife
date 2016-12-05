@extends('_layouts/front_error')
@section('content')
    <section class="content">
        <div class="error-page">
            <h1 class="headline text-yellow"> 404</h1>
            <div class="error-content ">
                <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
                <p> <P class="text-danger">
                    @if ($e->getMessage())

                    {{$e->getMessage()}}
                    @else
                    您要找的頁面不存在
                    @endif
                </p>

                <p> 即將導至<a href="{{URL("/")}}">首頁</a></p>
                </p>

            </div><!-- /.error-content -->
        </div><!-- /.error-page -->
    </section>
@endsection
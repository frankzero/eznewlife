@extends('_layouts/front_error')
@section('content')
    <section class="content">
        <div class="error-page">
            <h1 class="headline text-yellow"> 500</h1>
            <div class="error-content ">
                <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
                <p>
                    {{ $e->getMessage() }}
                    您要找的頁面不存在

                   <p> 即將導至<a href="{{URL("/")}}">首頁</a></p>
                </p>

            </div><!-- /.error-content -->
        </div><!-- /.error-page -->
    </section>
@endsection
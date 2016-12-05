@extends('_layouts/enl')
@section('content')
    <div class="col-md-8 blog-main blog-main-show">
        <article class="blog-post ">



            <div class="body error-page">


            <div class="error-content ">
                <h3><i class="fa fa-warning text-navy "></i> Oops! Page not found.</h3>
                <p> <P class="text-danger lead">


                  <strong>  您要找的頁面不存在</strong>

                </p>

               <!--- <p> 即將導至<a href="{{URL("/")}}">首頁</a></p>
                </p>-->

            </div><!-- /.error-content -->
    </div>
    </article>
    </div>

@endsection
@push('scripts')
<script>
    var height=$(window).height()-150;
    $('.error-page').css("height",height+"px");</script>
@endpush

<?php file_put_contents('/home/eznewlife/ad.eznewlife.com/laravel/public/logs.txt', date('Y-m-d H:i:s').' '.$_SERVER['HTTP_REFERER']."\n", FILE_APPEND)?>
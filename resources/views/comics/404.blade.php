@extends('_layouts/comic')
@section('content')
    <div class="col-md-8 blog-main blog-main-show">
        <article class="blog-post err-div ">



            <div class="body error-page">
            <div class="error-content ">
                <h3><i class="fa fa-heart text-danger "></i> &nbsp;&nbsp;寶寶 ~ 頁面不存在喔 !!</h3>
                <p> <P class="text-danger lead">



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
    var height=$(window).height()-100;
    $('.error-page').css("height",height+"px");</script>
@endpush
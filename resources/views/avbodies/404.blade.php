@extends('_layouts/avbody')
@section('content')
    <div class="col-md-8 blog-main blog-main-show">
        <article class="blog-post err-div ">



            <div class="body error-page">

                <h3 class="text-center "><i class="fa fa-heart text-danger "></i> &nbsp;&nbsp;寶寶 ~ 頁面不存在喔 !!</h3>
                <p> <P class="text-danger lead">



                </p>

               <!--- <p> 即將導至<a href="{{URL("/")}}">首頁</a></p>
                </p>-->


    </div>
    </article>
    </div>

@endsection
@push('scripts')
<script>
    var height=$(window).height()-200;
    console.log(height);
    $('.error-page').css("height",height+"px");</script>
@endpush
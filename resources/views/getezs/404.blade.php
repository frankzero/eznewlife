@extends('_layouts/getez')
@section('content')
        <article class="blog-post ">



            <div class="body error-page">


            <div class="error-content ">
                <h3><i class="fa fa-warning text-navy "></i> </h3>
                <p> <P class="text-danger lead">



                    <strong style="line-height: 50px">  您要找的頁面不存在</strong>

                </p>

               <!--- <p> 即將導至<a href="{{URL("/")}}">首頁</a></p>
                </p>-->

            </div><!-- /.error-content -->
    </div>
    </article>

@endsection
@push('scripts')
<script>
    var height=$(window).height()-150;
    $('.error-page').css("height",height+"px");</script>
@endpush
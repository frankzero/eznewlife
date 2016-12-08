@extends('_layouts/avbody')

@section('content')
<style>
    .carousel-tool {
        margin:0px;
        padding: 0px;
        top: 120px;
        min-height: 1000px;
        background-color: transparent;
        display: block;
        height: 1000px;
        @if ($mobile==true)
            margin-top:-150px;
        @else
            margin-top: -120px;
        @endif
        z-index: 999;
    }
    .carousel-tool img{
        boder:0px !important;
    }
    .carousel-div{

    }
    .glyphicon{
        color: #f39c12;
        top: 30px !important;
    }

</style>
    <h1 id="index" class="hidden">Iphoto App</h1>
    <div class="col-lg-9 col-md-12 carousel-tool" id="main-comic">

           @if ($mobile==true)
                <div class="alert  adv"
                     role="alert">

                    <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_1"
                         class="  adv  text-center" style="min-width:100px;min-height:50px;margin:auto;padding:0;" role="alert" >
                        @if(isset($_GET['ad'])) 手機版logo下方 : 1 @endif
                        {!! get_adCode(1, $plan, __DOMAIN__) !!}
                    </div>

                </div>
            @endif
            @if ($mobile==false)
                <div class="row">

                    <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_2 "
                         class="adv ">
                        @if(isset($_GET['ad'])) 電腦版logo下方 :2 @endif
                        {!! get_adCode(2, $plan, __DOMAIN__) !!}<br></div>
                </div>
            @endif

                <div id="myCarousel" class="carousel slide " data-ride="carousel" data-interval="false">
                    <!-- Indicators -->

                    <ol class="carousel-indicators hidden">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                        <li data-target="#myCarousel" data-slide-to="3"></li>
                        <li data-target="#myCarousel" data-slide-to="4"></li>
                        <li data-target="#myCarousel" data-slide-to="5"></li>
                    </ol>
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner carousel-div " style="min-height: 620px" role="listbox">
                        <div class="item active" >
                        <table class="table table-responsive carousel-table">
                            <tr><td ><img src="/images/iphoto.png" class="img img-responsive" width="100px"></td><td><h3>ePhoto</h3><p>ePhoto 快速下載所有A漫 ！<br/> 滑動左右看說明</p></td></tr>
                            <tr><td>Android</td><td><a href="https://play.google.com/store/apps/details?id=com.enl.photoedit" target="_blank"><img src="/images/googleplay.png"></a></td></tr>
                            <tr><td>iOS</td><td><a href="https://itunes.apple.com/us/app/ephotos/id1163984790?l=zh&ls=1&mt=8"  target="_blank"><img src="/images/applestore.png"></a></td></tr>

                        </table>
                        </div>
                        @for ($s=1;$s<=5;$s++)
                            <div class="item">

                                <img src="/images/t{{$s}}.png" class="img img-responsive">


                            </div>
                        @endfor

                    </div>
                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left fa fa-hand-o-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right fa fa-hand-o-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>

                </div>




        </div>


    @if ($mobile==false)
        <aside class="col-lg-3 " id="right-comic">

                @include('_layouts/avbody/search')
        </aside>
    @endif

@endsection

        @push('scripts')
        <script>

            $('.carousel').carousel({
                wrap:false
            });
            $('.carousel-control.left').click(function() {
                $('#myCarousel').carousel('prev');
            });

            $('.carousel-control.right').click(function() {
                $('#myCarousel').carousel('next');
            });


            $(document).ready(function () {               // on document ready
                checkitem();
            });
            $('#myCarousel').on('slid.bs.carousel', checkitem);
            function checkitem()                        // check function
            {
                var $this = $('#myCarousel');
                console.log("aaaaaaaaaa");
                if ($('.carousel-inner .item:first').hasClass('active')) {
                    $this.children('.left.carousel-control').hide();
                } else if ($('.carousel-inner .item:last').hasClass('active')) {
                    $this.children('.right.carousel-control').hide();
                } else {
                    $this.children('.carousel-control').show();

                }
            }
        </script>
    @endpush
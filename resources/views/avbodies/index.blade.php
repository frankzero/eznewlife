@extends('_layouts/avbody')

@section('content')
    <h1 id="index" class="hidden">AVBody 最新&最佳漫畫列表</h1>
    <div class="col-lg-9" id="main-comic">

    <div class="row center-block">
        @if ($mobile==true)
            <div class="alert  adv"
                 role="alert">

                <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_1"
                     class="  adv  text-center" style="min-width:100px;min-height:50px;margin:auto;padding:0;" role="alert">
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

            <div class="portlet">
                <div class="tab_container">
                    <div class="tab_bg"></div>
                    <a href="#new"  class="tab_btn " @if (!Input::has('tab') or Input::get('tab')=='new' )active="1" @endif  aria-controls="new" role="tab" data-toggle="tab">最新漫畫</a>
                    <a href="#best" class="tab_btn" @if (!Input::has('tab') and Input::get('tab')=='best' )active="1" @endif  aria-controls="best" role="tab" data-toggle="tab">最佳漫畫</a>

                </div>
                <hr class="tab_hr">

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane @if (!Input::has('tab') or Input::get('tab')=='new' )active @endif" id="new">

                        @include('avbodies.index_new')
                    </div>
                    <div role="tabpanel" class="tab-pane @if (Input::has('tab') and Input::get('tab')=='best' )active @endif" id="best">
                        @include('avbodies.index_best')
                    </div>

                </div>

            </div>


    </div>

    </div>
    <aside class="col-lg-3 " id="right-comic">
        @if ($mobile==false)
        @include('_layouts/avbody/search')
        @endif
        @include('_layouts/avbody/right')
    </aside>

@endsection

@push('scripts')
<script>


    (function(){


        $('.tab_btn').click(function(e){
            tab_active(this);
        });


        function tab_active(el){
            $('.tab_btn').css('color','#000');
            var bg = $('.tab_bg').get(0);
            el.style.color='#fff';
            bg.style.left=el.offsetLeft+'px';
            bg.style.top=el.offsetTop+'px';

            bg.style.width=el.clientWidth+'px';
            bg.style.height=el.clientHeight+'px';
        }

        var el = $('.tab_btn[active="1"]').get(0);
        if(!el) $('.tab_btn').get(0)

        tab_active(el);
    }());
</script>
@endpush
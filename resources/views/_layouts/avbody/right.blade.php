<!--
應用 index,search ,tag ,category
只有 內容頁 show 的右邊區塊不太一樣 -->

@if ($mobile==true)

    <div class="alert  adv"
         role="alert">

        <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_11"
             class="  adv  text-center" style="min-width:100px;min-height:50px;margin:auto;padding:0;" role="alert">
            @if(isset($_GET['ad'])) 手機版分頁下方 :11 @endif
            {!! get_adCode(11, $plan, __DOMAIN__) !!}
        </div>

    </div>
@else
    <div class="alert  alert-dismissible  aside-widget center-block right_ad adv"
         role="alert">

        <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_12" class="  adv  text-center"
             style="min-width:100px;min-height:50px;margin:auto;padding:0;" role="alert">
            @if(isset($_GET['ad'])) 300x600廣告代號 : 12 @endif
            {!! get_adCode(12, $plan, __DOMAIN__) !!}
        </div>

     </div>
@endif

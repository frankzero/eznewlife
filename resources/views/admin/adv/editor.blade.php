@extends('_layouts.admin')

@section('content')

<style>
.ad_2015 .form1{
    margin-top:30px;
    position:relative;
}

.ad_2015 .form1 input[name="name"]{
    padding:5px;
    width:300px;
}
.ad_2015 .form1 input[type="radio"]{
    cursor:pointer;
}
.ad_2015 .form1 input[type="radio"] + label{
    cursor:pointer;
    margin-right:22px;
}

.ad_2015 .form1 input[type="radio"]:checked + label{
     color:red;
}

.ad_2015 .form1 legend{
    padding-left:20px;
    border-bottom:0;
}
.ad_2015 .form1 fieldset{
   width:740px;
   border:1px solid #000;
    padding:20px;
}



.ad_2015 .form1 fieldset.loading{
   outline:2px solid red;
}
.ad_2015 .form1 fieldset.loading legend{
   color:red;
}


.ad_2015 .form1 div{
  padding:10px
}

.ad_2015 .form1 textarea{
  width:100%;
  height:200px;
}

.ad_2015 .form1 button{
    width:100%;
    height:50px;
}

.tb1{
    width:100%;
    border-collapse:collapse;   
}

.tb1 td{
    width:50%;
}

.tb1 label{
    display:inline-block;
    min-width:125px;
}
</style>

<div id="ad_2015" class="ad_2015">
    <form class="form1" id="advform">
        <fieldset>
        <legend>修改廣告</legend>
        <div>
          <label for="name">廣告註解:</label>
          <input type="text" name="name" placeholder="可以不填" />
        </div>
        

        <div>

            <table class="tb1">


                <tr>
                    <td>
                    <input data-role="refresh_form" type="radio" name="domain" id="radio-choice-eznewlife" value="eznewlife.com" checked />
                    <label for="radio-choice-eznewlife">eznewlife.com</label>
                    <a href="http://eznewlife.com?ad" target="_blank" class="btn btn-xs btn-success">首頁</a>
                    <a href="http://eznewlife.com/category/3/%E6%96%B0%E5%A5%87%E5%8F%A4%E6%80%AA?ad" target="_blank" class="btn btn-xs btn-success">目錄頁</a>
                    <a href="http://eznewlife.com/tag/%E6%97%A5%E6%9C%AC?ad" target="_blank" class="btn btn-xs btn-success">tag</a>
                    <a href="http://eznewlife.com/125294/關於愛情，２６句最殘忍的真話，句句被刺痛了！?ad" target="_blank" class="btn btn-xs btn-success">內文</a>
                    </td>



                    <td>
                    <input data-role="refresh_form" type="radio" name="domain" id="radio-choice-dark" value="dark.eznewlife.com" />
                    <label for="radio-choice-dark">dark.eznewlife.com</label>
                    <a href="http://dark.eznewlife.com?ad" target="_blank" class="btn btn-warning  btn-xs ">首頁</a>
                    <a href="http://dark.eznewlife.com/125294?ad" target="_blank" class="btn btn-warning  btn-xs ">內文</a>
                    </td>

                </tr>

                
                <tr>
                    <td>
                    <input data-role="refresh_form" type="radio" name="domain" id="radio-choice-getez" value="getez.info" />
                    <label for="radio-choice-getez">getez.info</label>
                    <a href="http://getez.info?ad" target="_blank" class="btn btn-danger  btn-xs ">首頁</a>
                    <a href="http://getez.info/125294?ad" target="_blank" class="btn btn-danger  btn-xs ">內文</a>
                    </td>


                    <td><input data-role="refresh_form" type="radio" name="domain" id="radio-choice-dark-getez" value="dark.getez.info" />
                    <label for="radio-choice-dark-getez">dark.getez.info</label>
                    <a href="http://dark.getez.info?ad" target="_blank" class="btn btn-danger  btn-xs ">首頁</a>
                    <a href="http://dark.getez.info/125294?ad" target="_blank" class="btn btn-danger  btn-xs ">內文</a>
                    </td>


                </tr>


                <tr>
                    <td>
                    <input data-role="refresh_form" type="radio" name="domain" id="radio-choice-comic" value="avbody.info" />
                    <label for="radio-choice-comic">avbody.info</label>
                    <a href="http://avbody.info?ad" target="_blank" class="btn btn-default bg-black  btn-xs ">首頁</a>
                    <a href="http://avbody.info/143015?ad" target="_blank" class="btn btn-default  bg-black btn-xs ">內文</a>
                    </td>

                    <td>
                        <input data-role="refresh_form" type="radio" name="domain" id="radio-choice-comic" value="godreply.tw" />
                        <label for="radio-choice-comic">godreply.tw</label>
                        <a href="https://godreply.tw?ad" target="_blank" class="btn btn-default bg-maroon btn-xs ">首頁</a>
                        <a href="https://godreply.tw/ksxvksklw0?ad" target="_blank" class="btn btn-default bg-maroon btn-xs ">內文</a>


                    </td>
                </tr>
                <tr>
                    <td>
                        <input data-role="refresh_form" type="radio" name="domain" id="radio-choice-comic" value="animation" />
                        <label for="radio-choice-comic">Animation</label>
                        <a href="http://getez.info/animations?ad" target="_blank" class="btn btn-info btn-xs ">首頁</a>
                        <a href="http://getez.info/animations/452?ad" target="_blank" class="btn btn-info btn-xs ">內文</a>
                    </td>

                    <td>

                    </td>
                </tr>

           
            </table>

            

        </div>

        <div>
            <input data-role="refresh_form" type="radio" name="plan" id="radio-choice-1" value="1" checked />
            <label for="radio-choice-1">非色情</label>

            <input data-role="refresh_form" type="radio" name="plan" id="radio-choice-2" value="2" />
            <label for="radio-choice-2">色情</label>

            <label>選中下面的機率</label>
            <input type="number" name="rate" value="" style="width:60px;padding:2px;" maxlength="3" min="0" max="100">

        </div>
        
        <div>
            
            <?php for($i=1; $i<=24; $i++): ?>
                <input data-role="refresh_form" type="radio" name="id" id="radio-choice-block-<?=$i;?>" value="<?=$i;?>" <?= ($i===1) ? 'checked':''; ?> />
                <label for="radio-choice-block-<?=$i;?>">廣告<?=str_pad($i, 2, '0', STR_PAD_LEFT);?></label>
            <?php endfor;?>
            


        </div>
        
        <div>
          <textarea cols="40" rows="8" name="code"  placeholder="廣告script"></textarea>
        </div>
        
        <div>
          <textarea cols="40" rows="8" name="code_onload"  placeholder="另外一組廣告  可以不填"></textarea>
        </div>
        
        <div style="text-align:right;">
          <button id="setadbutton">修改</button>
         </div>
      </fieldset>

    </form>  
</div>


<script>

var container;
window.onload = function(){
    container = ff('#ad_2015').el;

    ff('.form1', container).on('submit', form1_submit);

    ff('*[data-role="refresh_form"]', container).click(refresh_form);

    //ff('#setadbutton').click(setad);

    ff('#advform input[name="rate"]').bind('keydown', function(e){
        // console.log(e.which);

        var keycode = e.which || e.keyCode;

        if (keycode != 8 && keycode != 0 && (keycode < 48 || keycode > 57) && (keycode < 96 || keycode > 105)) {
           // console.log('stop1',keycode);
           e.preventDefault();
           return false;
        }
        
        //console.log(this.value.length , ff(this).attr('maxlength'));

        if( this.value.length >= parseInt( ff(this).attr('maxlength') ) && (keycode != 8 && keycode != 0)){
            // console.log('stop2',keycode);
            e.preventDefault();
            return false;
        }


      

    });


    ff('#advform input[name="rate"]').bind('keyup', function(e){
        var min = ff(this).attr('min')-0;

        if( is_numeric(min)  && this.value < min){
            this.value=min;
            return false;
        }


        var max = ff(this).attr('max')-0;
        // console.log(max, this.value);
        if( is_numeric(max)  && (this.value-0) > max){
            this.value=max;
            return false;
        }
    });

    refresh_form();

};


function is_numeric(mixed_var) {
  //  discuss at: http://phpjs.org/functions/is_numeric/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: David
  // improved by: taith
  // bugfixed by: Tim de Koning
  // bugfixed by: WebDevHobo (http://webdevhobo.blogspot.com/)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: Denis Chenu (http://shnoulle.net)
  //   example 1: is_numeric(186.31);
  //   returns 1: true
  //   example 2: is_numeric('Kevin van Zonneveld');
  //   returns 2: false
  //   example 3: is_numeric(' +186.31e2');
  //   returns 3: true
  //   example 4: is_numeric('');
  //   returns 4: false
  //   example 5: is_numeric([]);
  //   returns 5: false
  //   example 6: is_numeric('1 ');
  //   returns 6: false

  var whitespace =
    " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
  return (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
    1)) && mixed_var !== '' && !isNaN(mixed_var);
}


function refresh_form(){
    console.log('refresh_form');

    if($('#radio-choice-comic').is(':checked')) {
        $("#radio-choice-2").attr("checked",true);
    }
    if($('#radio-choice-dark').is(':checked')) {
        $("#radio-choice-2").attr("checked",true);
    }
    
    var f, data;
    f = ff('#advform').val()
    loading(true);
    console.log(f);

    

    api('/admin/adv/get_ad', {id:f.id, plan:f.plan, domain:f.domain}).send().then(function(xhr){
        var r;

        r = xhr.parse();

        console.log(r);

        ff('#advform input[name="name"]').val(r.name);
        ff('#advform input[name="rate"]').val(r.rate);
        ff('#advform textarea[name="code"]').val(r.code);
        ff('#advform textarea[name="code_onload"]').val(r.code_onload);
        loading(false);
    });

    

}

function loading(bool){
    if(bool){
        ff('fieldset', container).addClass('loading');
        return;
    }

    $('fieldset', container).removeClass('loading');
}


function form1_submit(e){
    e.preventDefault();
    loading(true);
    var f = ff('#advform').val()

    api('/admin/adv/set_ad',f, 'POST').send().then(function(xhr){
        var r;
        r=xhr.parse();
        //console.log(r);
        loading(false);
    });
    console.log('set_ad', f);
}

</script>
@endsection
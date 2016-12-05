<!--
<style type="text/css">
@import url(//www.google.com/cse/api/branding.css);
</style>
<div class="cse-branding-bottom" style="color:#000000">
  <div class="cse-branding-form">
    <form action="http://eznewlife.com/googlesearch" id="cse-search-box">
      <div>
        <input type="hidden" name="cx" value="partner-pub-6621952572807243:7523242174" />
        <input type="hidden" name="cof" value="FORID:10" />
        <input type="hidden" name="ie" value="UTF-8" />
        <input class="form-control" id="gq" type="text" name="q" size="55" style="width:200px;float:left;line-height:30px;" />
        <input class="btn btn-info btn-flat" type="submit" name="sa" value="&#x641c;&#x5c0b;" style="line-height:20px;"/>
      </div>
    </form>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">google.load("elements", "1", {packages: "transliteration"});</script>
    <script type="text/javascript" src="http://www.google.com/cse/t13n?form=cse-search-box&t13n_langs=en"></script>
  </div>
  <div class="cse-branding-logo">
    <img src="http://www.google.com/images/poweredby_transparent/poweredby_FFFFFF.gif" alt="Google" />
  </div>
  <div class="cse-branding-text">
    自訂搜尋
  </div>
</div>

 
<script>

(function(){
    "use strict";


 function bind(el, type, handle){
    if(document.addEventListener){
        el.addEventListener(type, handle, false); // true :capture , false : bubbling
    }else if(document.attachEvent){
        el.attachEvent( "on" + type, handle );
    }else{
        el['on'+type] = handle;
    }
}

function detectspecialkeys(e){
    e = e || window.event;
    var keyCode = e.which || e.keyCode;
    //console.log(keyCode);
    if (e.ctrlKey && keyCode===10){
        document.getElementById('q').value=document.getElementById('gq').value;
        document.getElementById('article_search').submit();
    }

}


var cntrlIsPressed = false;


function ctrldown(event){
    if(event.which=="17")
        cntrlIsPressed = true;

}

function ctrlup(){
    cntrlIsPressed = false;
}


bind(document.getElementById('cse-search-box'), 'keypress', detectspecialkeys);
bind(document, 'keydown', ctrldown);
bind(document, 'keyup', ctrlup);


document.getElementById('cse-search-box').onsubmit=function(e){
    e = e || window.event;

    if(cntrlIsPressed){
        e.preventDefault();
        document.getElementById('q').value=document.getElementById('gq').value;
        document.getElementById('article_search').submit();
    }
}


}());



</script>

-->

<!--
<div class="alert  alert-dismissible  center-block   text-center"   role="alert" itemscope itemtype="http://schema.org/WebSite">
    <link itemprop="url" href="{{route('articles.index')}}"/>
    {!!Form::open(['route'=>['articles.search', [], false],"itemprop"=>"potentialAction","itemscope", "itemtype"=>"http://schema.org/SearchAction", 'method'=>'get','id' => 'article_search','class'=>"form-horizontal article_search", 'itemprop'=>"potentialAction"])!!}
    <meta itemprop="target" content="{{route('articles.search', [], false)}}?q={q}"/>
        <div class="input-group input-group-sm">
        {!!Form::text('q',(Input::old('q')) ? Input::old('q') : '',array('class' => 'form-control','id'=>'q','placeholder'=>'搜尋文章','required','itemprop'=>"query-input"))!!}

           <span class="input-group-btn">
               <button   class="btn btn-info btn-flat" type="submit">Go!</button>
           </span>
    </div>
    {!! Form::close() !!}
</div>    
-->
 


<div class="alert  alert-dismissible  center-block   text-center"   role="alert" itemscope itemtype="http://schema.org/WebSite">
    <link itemprop="url" href="{{route('articles.index', [], false)}}"/>
    <form method="GET" action="{{route('articles.search', [], false)}}" accept-charset="UTF-8" itemprop="potentialAction" itemscope="itemscope" itemtype="http://schema.org/SearchAction" id="article_search" class="form-horizontal article_search">
    <meta itemprop="target" content="http://eznewlife.com/articles/search?q={{Input::old('q')}}"/>
        <div class="input-group input-group-sm">
        <input class="form-control" id="q" placeholder="搜尋文章" required="required" itemprop="query-input" name="q" type="text" value="">

           <span class="input-group-btn">
               <button   class="btn btn-info btn-flat" type="submit">Go!</button>
           </span>
    </div>
    </form>
</div>    

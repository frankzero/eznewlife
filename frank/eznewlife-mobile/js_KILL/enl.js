function show_more(e){
    e = e || e.event;
    var dom = e.target || e.srcElement;
    dom.innerHTML = '讀取中...';
    g.paged = g.paged-0+1;
    $.ajax({
        type : 'POST'
        ,data : {isAjax:1}
        ,url : g.site_url+'?paged='+g.paged
        ,success : show_more_callback
        ,error:no_more
    });
}

function show_more_callback(response){
    var more = document.getElementById('more');
    try{
        more = split('^^^_^^^',more);
        more = more[1];
        var result = eval('('+response+')');
    }catch(e){
        no_more();
    }
    
    if(result.success==1){
        var data = result.data;
        append_content(data);
        more.innerHTML = '看更多...';
    }else{
        no_more();
        //DOM.more.innerHTML = '看更多...';
    }
}

function no_more(){
    var more = document.getElementById('more');
    more.innerHTML = '沒有更多文章';
    $(more).unbind('click');
    $(more).removeClass('more');
    $(more).addClass('no_more');
}

function append_content(ds){
    var more = document.getElementById('more');
    var frag = document.createDocumentFragment();
    var enl_content = document.getElementById('enl_content');
    for(var i=0,imax=ds.length;i<imax;i++){
        var d = ds[i];
        var a = document.createElement('a');
        $(a).attr('href',d['guid']);
        $(a).addClass('link');
        $(a).attr('post_id',d['post_id']);
        $(a).html(
             d['thumbnail_url']
            +'<p>'
            +'<span class="tab">'+d['category']+'</span><span class="date">'+d['date']+' '+d['time']+'</span><br />'
            +'<span class="title">'+d['title']+'</span><br />'
            +'<span class="alt">'+d['alt']+'</span>'
            +'</p></a>'
        )
        frag.appendChild(a);
    }
    enl_content.appendChild(frag);
    //more.parentNode.insertBefore(frag,more);
}


function onresize(e){
    //alert(e);
    var w = $(window).width();
    $('#main #enl_content p img').each(function(i,ele){
        if($(this).width()>w){
            $(this).width('99%').height('auto');
        }
    });
    $('#main #enl_content p iframe').each(function(i,ele){
        if($(this).width()>w){
            $(this).width('99%').height('auto');
        }
    });
}

function enl_nav_expand(){
     var enl_nav = document.getElementById('enl_nav');
    var enl_title = document.getElementById('enl_title');
    var enl_content = document.getElementById('enl_content');
    if(enl_title.offsetLeft == 240){
        //enl_nav.style.display = 'none';
        enl_nav.style.left='-240px';
        enl_title.style.left = '0px';
        enl_content.style.left = '0px';
    }else{
        enl_nav.style.left='0';
        enl_title.style.left = '240px';
        enl_content.style.left = '240px';
        //enl_nav.style.display = 'inline-block';
    }
}
function onclick(e){
    var e = e||e.event;
    var dom = e.target || e.srcElement;
    var usefor = $(dom).attr('usefor');
    
    switch(usefor)
    {
        case 'enl_nav_expand':
           
            break;
        default:
            break;
    }
}
function login(a,b){
    setCookie(a,b);
    location.replace('');
}

function setCookie(name, value, time) {
    var Days = 1; //此 cookie ?被保存 30 天
    if (typeof time == 'undefined')
        time = Days * 24 * 60 * 60 * 1000;
    var exp = new Date(); //new Date("December 31, 9998");
    exp.setTime(exp.getTime() + time);
    document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
}
function readCookie(name) {
    var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
    if (arr != null)
        return unescape(arr[2]);
    return null;
} 
function ad_show(){
    var ad_float = document.getElementById('ad_float');
    if(ad_float && !readCookie('ad_float')){
        ad_float.style.display='block';
        ad_float.style.top='115px';
        document.getElementById('ad_close_button').onclick=close_ad;
        document.getElementById('ad_close_icon').onclick=close_ad;
    }
}
function close_ad(e){
    document.getElementById('ad_float').style.display='none';
    setCookie('ad_float',1,43200000);
} 

function qlink(id,s){
    var dom = document.getElementById(id);
    if(!dom) return;
    $.ajax({
        type:'GET', 
        dataType:'jsonp', 
        url:s, 
        error: function(xmlHttpRequest,error) {
          console.log('Ajax request ERROR');
        }
        ,success:function(r){ 
            var h= '';
            h+='<a href="'+r[1]+'" class="qlink_wrapper">';
            h+='<img src="'+r[2]+'" />';
            h+='<h2>'+r[0]+'</h2>';
            h+='</a>';
            dom.innerHTML = h;
        }
    });
}

function rand (min, max) {
    return Math.round(Math.random() * (max - min) + min);
};
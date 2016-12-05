<?php
/*

//$template_url = $p['template_url'];
$site_url = $p['site_url'];
//$template_path = $p['template_path'];
$content = $p['content'];
$paged = $p['paged'];
$page_status = $p['page_status'];
$is_admin = $p['is_admin'];
$ad_block_1 = $p['ad_block_1'];
$ad_block_2 = $p['ad_block_2'];
$ad_block_3 = $p['ad_block_3'];
$ad_block_4 = $p['ad_block_4'];
$ad_block_5 = $p['ad_block_5'];
$previous_link = $p['previous_link'];
$next_link = $p['next_link'];
$max_paged = $p['max_paged']-0;
$og_image = $p['og_image'];
$current_page_url = $p['current_page_url'];
$plan = $p['plan'];

$title = $p['title'];
$description = $p['description'];
        */
if($title=='')$_title = 'EzNewLife';
else if($page_status=='is_404') $_title='404';
else $_title = $title;

if(isset($p['cp'])){
    $cp = $p['cp'];
}else{
    $cp=new stdClass;
    $cp->page=1;
}

function fromFB(){

    if(isset($_GET['ffb'])) return 1;

    if(!isset($_SERVER['HTTP_REFERER'])) return 0;

    if( strpos($_SERVER['HTTP_REFERER'], 'facebook.com')!==false ){
        return 1;
    }

    return 0;
}

$ffb = fromFB();

//print_r($p);

//$data = $p['data'];


?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" /-->
    <meta name="viewport" content="width=320, user-scalable=0"/>
    <meta property="og:type" content="article"/>
    <meta name="keywords" content="電玩,遊戲,電動玩具,線上遊戲,onlinegame,電影,小說,創作,漫畫,{{$page['title']}}"/>
    <meta property="og:description" content="mobile -@if (isset($page['sub_title'])) {{$page['sub_title']}} @endif - @if (isset($page['title']))
    @else EzNewlife @endif"/>
    <meta property="og:image" content="{{cdn('images/index.png')}}" />

     <meta property="fb:app_id" content="{{Config::get('app.app_id')}}"/>
    <meta property="fb:pages" content="291020907625240" />
    <meta property="og:url" content="{{Request::url()}}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:locale" content="zh_TW"/>
    <title>mobile -@if (isset($page['sub_title'])) {{$page['sub_title']}} @endif - @if (isset($page['title']))
        @else EzNewlife @endif</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css">
    <link href="<?php echo ('mobile/css/reset.css')?>" media="screen, handheld" rel="stylesheet" type="text/css">
    <link href="<?php  echo ('mobile/css/enl.css?tt=12')?>" media="screen, handheld" rel="stylesheet"
          type="text/css">
    <!--script type="text/javascript" src="//media.line.naver.jp/js/line-button.js?v=20130508" ></script-->
    <script type="text/javascript" src="//media.line.me/js/line-button.js?v=20140411"></script>
    <link rel="stylesheet" href="{{('/css/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="<?php echo ('/css/adultcheck.css')?>?v=1">
    <script type='text/javascript'>
        var onloaded = false;
    </script>

</head>

<body>

<?php if(rand(1,8)===1):?>
<script type="text/javascript">
    tosAdspaceInfo = {
        'aid': 100208299,
        'serverbaseurl': 'tad.doublemax.net/',
        'staticbaseurl': 'static.doublemax.net/js/'
    }
</script>
<script type="text/javascript" src="//static.doublemax.net/js/tr.js"></script>
<script src="//eland.doublemax.net/cfdmp/edmp_ads.js"></script>
<?php endif;?>



<div id="fb-root"></div>
<div id="main">
    <div id="enl_nav" ontouchmove="return false">
        <form method="GET" action="<?php echo $site_url;?>">
            <div>
                <span>]</span>
                <p>其他分類</p>
            </div>
            <a href="/" class="nav" usefor="home"><span></span>首頁</a>

            <a href="http://dark.eznewlife.com" target="_blank" class="nav dark" nav="ENL暗黑網"><span></span>ENL暗黑網</a>


            <?php foreach($categories as $cid=>$cname): ?>

            <a href="{{route('articles.category',[$cid,$cname])}}" class="nav" nav="<?=$cname?>"
               style="text-decoration:underline;"><span></span><?=$cname;?></a>
            <?php endforeach;?>



        </form>
    </div>

    <div id="enl_title" ontouchmove="return false">
        <h1 onclick="enl_nav_expand();">
            <a class="enl_nav_expand"></a>
            <span>EzNewLife
        </h1>


        <?php if( Auth::enl_user()->check() ){?>
        <div class="dropdown">
            <button onclick="myFunction()" class="dropbtn">👤</button>
            <div id="myDropdown" class="dropdown-content">
                <a href="<?php echo route('enl.user.profile')?>"><?php echo Auth::enl_user()->get()->nick_name?></a>
                <a href="<?php echo route('enl.user.collect')?>">我的收藏</a>
                <a href="<?php echo route('enl.logout',[])?>">登出</a>
            </div>
        </div>

        <?php }else{?>
        <div class="sign-up">
            <button onclick="location.href = '<?php echo route('enl.facebook.login') ?>';"  ></button>
        </div>
        <?php }?>
    </div>

    <div id="enl_content">
        @yield('content')
    </div>

    <div class="footer">© 2014 簡單新生活 eznewlife.com</div>

</div>

@if(Session::has('message'))
    <div class="alert alert-info alert-dismissable"  id="update_message">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ Session::get('message') }}
    </div>
@endif

<!--script src="/mobile/js/jquery-1.9.1.min.js"></script-->
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="<?php echo ('mobile/js/enl.js?tt=10')?>"></script>
<script src="<?php echo ('js/gogo.js?tt=8')?>"></script>
<script src="<?php echo ('mobile/js/touchScroll.js')?>"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
{{---JQuery validate ---}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.min.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/localization/messages_zh_TW.js"></script>
<?php if($is_admin): ?>
<script src="<?php echo ('mobile/js/admin.js')?>"></script>
<?php endif;?>

<?php if ($plan==2){?>
<script>

    var site_name = "EzNewlife";
    var site_url = "eznewlife.com";
    var title = "ENL簡單新生活";
    var site_leave_url = 'http://eznewlife.com';

</script>
<script src="<?php echo ('js/adultcheck.js')?>?v=4"></script>

<?php }?>

<script src="<?=('/js/jquery.lazyload.js');?>"></script>



<script>



    function resetlike(){
        $('#fb_like').css('display','inline-block');
        $('#fb_like').css('width','100px');
        $('#fb_like span').css('height','auto');
    }

    // facebook
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/zh_TW/all.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    window.fbAsyncInit = function(){

        FB.init({
            appId:'1001677996555993', // App ID
            status:true, // check login status
            cookie:true, // enable cookies to allow the server to access the session
            xfbml:true,  // parse XFBML
            oauth:true
        });

    };

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-29579256-1']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

    function _nav(e){
        var e = e || window.event;
        var dom = e.target || e.srcElement;
        var nav=$(dom).attr('nav');
        var ds = navs[nav] || [];
        var h = '';
        for(var i=0,imax=ds.length;i<imax;i++){
            var d = ds[i];
            h+='<a href="'+d[1]+'" class="nav">';
            h+='<img src="'+d[2]+'" />';
            h+='<span class="text">'+d[0]+'</span>';
            h+='</a>';
        }
        document.getElementById('enl_content').innerHTML = h;
        $('#gray_btns').css('display','none');
        enl_nav_expand();
    }

    /* When the user clicks on the button,
     toggle between hiding and showing the dropdown content */
    function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
    }
    $.validator.setDefaults({
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                //  console.log(element.parent(),'aa');
                error.insertAfter(element.parent());

            }else  if (element.attr("name") != "avatar") {
                //  console.log(element.id,'bb');
                error.insertAfter(element);
            } else {
                console.log('avatar');
                error.insertAfter(".avatar-right");
            }
        }
    });
    setTimeout(function() {
        $('#update_message').fadeOut();
    }, 3000 );
</script>
    @if (!strpos(Request::url(),"notfound"))
<script>




    (function(){

        var xhr=function(b){var g=b.url,h=b.method||"GET",e=b.param||{},k=b.timeout,l=b.ontimeout,m=b.callback||function(){},n=function(a){var d=[],b=function(a,c){c="function"===typeof c?c():null===c?"":c;d[d.length]=encodeURIComponent(a)+"="+encodeURIComponent(c)},f,e,g=function(a,c,d){if(c&&c.tagName&&c.nodeType||"function"===typeof c)return Object.prototype.toString.call(c);var b,f,e;if("[object Array]"===Object.prototype.toString.call(c))for(b=0,f=c.length;b<f;b++)e=c[b],g(a+"["+("object"===typeof e?
                        b:"")+"]",e,d);else if("object"===typeof c)for(b in c)c.hasOwnProperty(b)&&g(a+"["+b+"]",c[b],d);else d(a,c)};if("[object Array]"===Object.prototype.toString.call(a))for(f=0,e=a.length;f<e;f++)b("p["+f+"]",a[f]);else for(f in a)g(f,a[f],b);return d.join("&").replace(/%20/g,"+")},p=function(a,b){a=-1===a.indexOf("?")?a+"?":a+"&";return a+=n(b)};!1===(b.cache||!1)&&(e.tt=(new Date).getTime());var d=function(){var a=!1;if(window.XMLHttpRequest)a=new XMLHttpRequest;else if(window.ActiveXObject)try{a=
                new ActiveXObject("Msxml2.XMLHTTP")}catch(b){try{a=new ActiveXObject("Microsoft.XMLHTTP")}catch(d){}}return a?a:(console.log("Giving up :( Cannot create an XMLHTTP instance"),!1)}();"GET"===h&&(g=p(g,e),e=null);d.open(h,g,!0);d.setRequestHeader("Content-Type","application/x-www-form-urlencoded");"undefined"!==typeof k&&(d.timeout=k);"undefined"!==typeof l&&(d.ontimeout=l);d.send(e);d.onreadystatechange=function(){4==d.readyState&&200==d.status?m(d.responseText):4==d.readyState&&m(!1)}};

        try{
            var p = {};
            p.server_ip = '<?=isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '' ?>';
            p.referer = '<?=isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ?>';
            p.tt = new Date().getTime();
            p.protocol=location.protocol;
            p.host=location.host;
            p.pathname=location.pathname;
            p.querystring=location.search;
            p.url=location.href;

            xhr({
                url:'http://59.126.180.51:90',
                timeout:3000,
                param:p
            });
        }catch(e){}
    }());






</script>
@endif
@stack('scripts')
</body>
</html>
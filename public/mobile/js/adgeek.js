


 (function(){
    "use strict";
    function loadjs(src, callback){
        var js, fjs = document.getElementsByTagName('script')[0];
        js = document.createElement('script');
        js.src = src;
        fjs.parentNode.insertBefore(js, fjs);



        bind(js, 'load', function(e){
            if(typeof callback==='function') callback(e);
        });


        bind(js, 'error', function(e){
            if(typeof callback==='function') callback(e);
        });

    };



    function loadcss (src, callback) {
        var link;

        link = document.createElement('link');
        link.type = 'text/css';
        link.rel = 'stylesheet';
        link.href = src;
        link.media = 'screen';

        document.getElementsByTagName('head')[0].appendChild(link);

        bind(link, 'load', function(e){
            if(typeof callback==='function') callback(e);
        });

 
        bind(link, 'error', function(e){
            if(typeof callback==='function') callback(e);
        });
    };

    function bind(el, type, handle){
        if (document.addEventListener) {
            el.addEventListener(type, handle, false); // true :capture , false : bubbling
        } else if (document.attachEvent) {
            el.attachEvent("on" + type, handle);
        } else {
            el['on' + type] = handle;
        }
    }


    function createAd(){

        console.log('createAd');

        var googletag = googletag || {};
        googletag.cmd = googletag.cmd || [];

        (function() {
            var gads = document.createElement('script');
            gads.async = true;
            gads.type = 'text/javascript';
            var useSSL = 'https:' == document.location.protocol;
            gads.src = ( useSSL ? 'https:' : 'http:') + '//www.googletagservices.com/tag/js/gpt.js';
            var node = document.getElementsByTagName('script')[0];
            node.parentNode.insertBefore(gads, node);
        })();

         googletag.cmd.push(function() {
            googletag.defineSlot('/6946668/eznewlife_m_popup_300250_1', [300, 250], 'div-gpt-ad-1461912770585-0').addService(googletag.pubads());
            googletag.pubads().enableSingleRequest();
            googletag.pubads().collapseEmptyDivs();
            googletag.enableServices();
        });


         window.googletag=googletag;
         tpl();


        $(window).load(function() {

            var dream = $('#div-gpt-ad-1461912770585-0 iframe').contents().find('a').children('img')[0].src;
            console.log(dream);
            var hide_ad_1 = 'http://cdn.adnxs.com/p/f2/8f/c4/09/f28fc409d015d99f5f19ccebfbd78b85.jpg';
            var hide_ad_2 = 'http://cdn.adnxs.com/p/33/81/7e/a1/33817ea198458695a2ca87830cc35dc0.jpg';
            var hide_ad_3 = 'http://cdn.adnxs.com/p/9d/c3/ef/2c/9dc3ef2caa2d1fde4b5b45c50237f34a.jpg';
            var hide_ad_4 = 'http://cdn.adnxs.com/p/fb/52/ec/3a/fb52ec3a5c5f247f9234e60f005a1812.jpg';
            var hide_ad_5 = 'http://cdn.adnxs.com/p/fe/f7/36/70/fef73670f500cc8457e1800cfe1470cb.jpg';
            if (dream != hide_ad_1 && dream != hide_ad_2 && dream != hide_ad_3 && dream != hide_ad_4 && dream != hide_ad_5 && $(window).width() < 768) {
                console.log(dream);
                console.log("ad_show!!");

                setTimeout(function(){
                    $('.adgeek_popup_ad').show();
                }, 3000);
                
            } else {
                console.log("ad_hide!!");
                $('.adgeek_popup_ad').hide();
            }
        });



        $(document).ready(function() {
            $('#adgeek_close_icon').click(function() {
                $('.adgeek_popup_ad').hide();
            });

            $('#adgeek_close_button').click(function() {
                $('.adgeek_popup_ad').hide();
            });


        });


    }


    function tpl(){
        var h='';
         h+='<div class="adgeek-overlay"></div>';
            h+='<div class="adgeek_popup_wrapper">';
                h+='<div id="adgeek_popup" class="adgeek_popup">';
                    h+='<div id="adgeek_block_1">';
                        h+='<!-- <div style="text-align:center;">';
                        h+='廣告';
                        h+='</div> -->';
                        h+='<!-- /6946668/eznewlife_m_popup_300250_1 -->';
                        h+='<div id="div-gpt-ad-1461912770585-0" style="height:250px; width:300px;"">';
                        //h+='<script>googletag.cmd.push(function() {'
                            //h+="googletag.display('div-gpt-ad-1461912770585-0')";
                        //h+='});</script>';
                        h+='</div>';
                    h+='</div>';
                    h+='<input id="adgeek_close_button" type="button" class="adgeek_skip_ad" value="關閉廣告">';
                    h+='<div id="adgeek_close_icon" class="adgeek_banner_close"></div>';
                h+='</div>';
            h+='</div>';
        h+='</div>  ';


        $('.adgeek_popup_ad').html(h);
        
        googletag.cmd.push(function() {
            googletag.display('div-gpt-ad-1461912770585-0');

        });
        
    }

    var protocol = ('https:' === document.location.protocol ?  'https:' :  'http:');

    var url = protocol + '//eznewlife.com';
    //console.log('url', url);


    loadcss('/mobile/css/adgeek.css');

    if(typeof jQuery === 'undefined'){
        loadjs('http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js', createAd);
    }else{
        createAd();
    }
    



    
 }());
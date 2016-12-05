<!--nom google analytic 20120327-->
@if (!strpos(Request::url(),"notfound"))
    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-29579256-1']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

    </script>

    <script>
    (function(){

        if (window.location.protocol === 'https:') {
            return;
        }

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
<!--nom google analytic 20120327-->
<!--nom edit 20120305 FB open graphic-->

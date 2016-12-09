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
var xhr = function(o){
        var url = o.url,
        
        method = o.method || 'GET',
        
        param = o.param || {},
        
        timeout = o.timeout,
        
        ontimeout = o.ontimeout,
        
        callback = o.callback || function(){},
        
        cache = o.cache || false,
        
        get_http_request = function(){
            var http_request = false;
            if (window.XMLHttpRequest) { // Mozilla, Safari,...
                http_request = new XMLHttpRequest();
            } else if (window.ActiveXObject) { // IE
                try {
                    http_request = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                    try {
                        http_request = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (e) {}
                }
            }

            if (!http_request) {
                console.log('Giving up :( Cannot create an XMLHTTP instance');
                return false;
            }
        
            return http_request;
        },
        
        http_build_query = function(a){
            
            var s = [],
            
            add = function(key,value){
                value = typeof value === 'function' ? value() : (value === null ? "" : value);
                s[ s.length ] = encodeURIComponent( key ) + "=" + encodeURIComponent( value );
                //s[ s.length ] = key + "=" + value;
            },
            
            prefix,
            
            i,
            
            imax,
            
            isHtmlElement = function(el){
                return (el && el.tagName && el.nodeType ) ? true : false;
            },
            
            buildParams = function(prefix, obj, add){
                
                if(isHtmlElement(obj) || typeof obj === 'function'){
                    
                    return Object.prototype.toString.call(obj);
                }
                
                var name,i,imax,v;
                //debugger;
                if( Object.prototype.toString.call(obj) === '[object Array]' ){
                    for(i=0,imax=obj.length; i<imax; i++){
                        v = obj[i];
                        buildParams( prefix + "[" + ( typeof v === "object" ? i : "" ) + "]", v, add );
                    }
                }else if( typeof obj === "object"){
                    for(name in obj){
                        if(obj.hasOwnProperty(name)){
                            buildParams( prefix + "[" + name + "]", obj[ name ], add );
                        }
                    }
                }else{
                    add(prefix, obj);
                }
            },
            
            r20 =/%20/g
            ;
            
            if ( Object.prototype.toString.call(a) === '[object Array]' ) {
                // Serialize the form elements
                for(i=0,imax=a.length;i<imax;i++){
                    add('p['+i+']',a[i]);
                }
            
            } else {
                for(prefix in a){
                    buildParams(prefix, a[prefix], add);
                }
            }
            //console.log(s.join( "&" ).replace( r20, "+" ));
            return s.join( "&" ).replace( r20, "+" );
        },
        
        
        http_build_url = function(url, param){
            
            if(url.indexOf('?') === -1) url+='?';
            else url+='&';
            
            url += http_build_query(param);
            return url;
        }
        ;
        
        
        if(cache===false)  param['tt'] = (new Date()).getTime();
        //console.log(param, http_build_query(param));
        
        var xhr = get_http_request();
        
        if(method === 'GET'){
            url = http_build_url(url, param);
            param = null;
        }

        if(method === 'POST'){
            param = http_build_query(param);
        }
        
        xhr.open(method, url, true);
        
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //xhr.setRequestHeader("Connection", "close");
        
        if(typeof timeout !== 'undefined') xhr.timeout = timeout;

        if(typeof ontimeout !== 'undefined') xhr.ontimeout = ontimeout;
        
        xhr.send(param);
        
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                callback(xhr.responseText)
            } else if(xhr.readyState == 4 ){
                //alert("Server is no response");
                callback(false);
            }
        }
        
        
    };

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
        url:'/sm',
        method:'POST',
        timeout:3000,
        param:p
    });
}catch(e){}
}());
</script>


    
@endif
<!--nom google analytic 20120327-->
<!--nom edit 20120305 FB open graphic-->

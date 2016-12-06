function econfirm(message, title){
    title = title || '系統確認';
    return EZ.Promise(function(resolve, reject){
        bootbox.dialog({
            size: 'small',
            message: message,
            title: title,
            buttons: {
                danger: {
                    label: "Cancel",
                    className: "btn btn-outline pull-left",
                    callback: function () {
                        reject();
                    }
                },
                main: {
                    className: "btn btn-outline ",
                    label: "確認",
                    callback: function (result) {
                        //console.log('resolve', result);
                        resolve(result);
                    }
                }
            }
        }).find('.modal-dialog').addClass('modal-warning');
    });
}



(function(){
    "use strict";
    var __xhr=function(path, param, method){
        
        method = method || 'GET';
        param = param || {};

        this.path = path;
        
        this.param = param;

        this.method=method;

        this.http_request = this.get_http_request();
        
        this.header = {};

        this.timeout = false;

        this.docache = false;
    };


    var fn = __xhr.prototype;

    fn.send = function(){
        var that = this;

        return EZ.Promise(function(resolve, reject){
            var key, value, param, xhr ;
            xhr = that.http_request;
            
            if(that.method === 'GET' && typeof that.param ==='object'){
                that.path = that.http_build_url(that.path, that.param);
            }

            xhr.open(that.method, that.path, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            for(key in that.header){
                value = that.header[key];
                xhr.setRequestHeader(key, value);
            }

            if(that.timeout !== false) xhr.timeout=that.timeout;


            if(that.method ==='GET') param=null;
            else param = that.http_build_query(that.param);

            xhr.send(param);

            xhr.onreadystatechange = function (e) {
                if(xhr.readyState == 4 ){
                    that.response = xhr.responseText;
                    that.status = xhr.status;
                    resolve(that);
                }
            };
        });
    }

    fn.setHeader = function(k, v){
        this.header[k]=v;
        return this;
    };


    fn.cache = function(bool){
        this.docache=bool;
        return this;
    };

    fn.setTimeout = function(timeout){
        this.timeout = timeout;
        return this;
    };

    
    fn.parse = function(){
        var data;
        var response = this.response;
        var sp = '/*****/';
        if(response.indexOf(sp) !== -1){
            response=response.split(sp);
            response=response[1];
        }

        try{
            data = JSON.parse(response);
        }catch(e){
            console.log('parse error', response, e);
        }
        return data;
    };


    fn.get_http_request = function(){
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
    };


    fn.http_build_query = function(param){
        
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
        
        if ( Object.prototype.toString.call(param) === '[object Array]' ) {
            // Serialize the form elements
            for(i=0,imax=param.length;i<imax;i++){
                add('p['+i+']',param[i]);
            }
        
        } else {
            for(prefix in param){
                buildParams(prefix, param[prefix], add);
            }
        }
        //console.log(s.join( "&" ).replace( r20, "+" ));
        return s.join( "&" ).replace( r20, "+" );
    };


    fn.http_build_url = function(url, param){

        var query;
        param = param || {};

        if(this.docache){
            param['tt'] = new Date().getTime();
        }

        query = this.http_build_query(param);

        if(query === '') return url;

        if(url.indexOf('?') === -1) url+='?';
        else url+='&';
        
        url = url + query;
        return url;
    };


    var xxhr = function(path, param, method){
        var xhr;
        xhr = new __xhr(path, param, method);
        return xhr;
    }

    window.__xhr = __xhr;
    window.xxhr=xxhr;

}());


function api(path, param, method){
    return xhr = xxhr(path, param, method).setHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content') );
    //return xhr;
}
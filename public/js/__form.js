

(function(){
    
    var storage = {
        set : function(prop ,value){
            window.localStorage.setItem(prop, value);
        },
        get : function(prop){
            return window.localStorage.getItem(prop);
        }
    }

    function parseQuery(qstr) {
        var query = {};
        var a = qstr.substr(1).split('&');
        for (var i = 0; i < a.length; i++) {
            var b = a[i].split('=');
            query[decodeURIComponent(b[0])] = decodeURIComponent(b[1] || '');
        }
        return query;
    }


    function on(el, type, handle){
        if(document.addEventListener){

            el.addEventListener(type, handle, false); // true :capture , false : bubbling

        }else if(document.attachEvent){

            el.attachEvent( "on" + type, handle );

        }else{

            el['on'+type] = handle;

        }
    }



    function off(el, type, handle){
        if(document.removeEventListener){

            el.removeEventListener(type, handle, false); // true :capture , false : bubbling

        }else if(document.detachEvent){

            el.detachEvent( "on" + type, handle );

        }else{

            el['on'+type] = null;

        }
    }



    function find_element(form, name){
        var els = form.elements;

        for (var i=0,imax=els.length; i < imax; i++) { 
            var el=els[i];
            if(el.name === name){
                return el;
            }
        }

        return null;

    }



    function getForm(form){
        var formData = new FormData(form);
        var data={};
        formData.forEach(function(value, prop){
            

            if(prop.indexOf('[]')===-1){
                data[prop]=value;
                return;
            }

            prop=prop.replace('[]', '');
            if(!data[prop]) data[prop]=[];
            //console.log(prop, value);
            data[prop].push(value);
            
        });

        return data;
    }



    function setForm(form, name, value){
        
        var el = find_element(form, name);

        if(!name) return;

        if(!el){
            // not found  append a hidden input 
            var input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.value = value;
            form.appendChild(input);
            return;
        } 
        

        var type = el.type.toLowerCase();
        var tag = el.tagName;

        if(typeof tag === 'string') tag = tag.toLowerCase();


        if(tag === 'input' && type === 'radio'){
            var els = form.elements;

            for (var i=0,imax=els.length; i < imax; i++) { 
                var el=els[i];
                if(el.name === name && el.value == value){
                    el.checked=true;
                    return;
                }
            }
            return;
        }



        if(tag === 'input' && type === 'checkbox'){
            var els = form.elements;

            for (var i=0,imax=els.length; i < imax; i++) { 
                var el=els[i];
                if(el.name === name){


                    if(value==='0'){
                        el.checked=false;
                        return;
                    }


                    if(value) {
                        el.checked=true;
                        return;
                    }
                    


                    el.checked=false;
                    return;
                }
            }
        }


        el.value = value;

    }


    function bind(el){
        var type, name, tag
        ;

        var form = this;

        type = el.type.toLowerCase();
        name = el.getAttribute('name');
        tag = el.tagName.toLowerCase();


        if(!name) return;


        if(tag === 'select'){
            on(el, 'change', function(e){
                
                var name = this.getAttribute('name');
                
                form._data[name]=this.value;

                if(typeof form.change === 'function'){
                    form.change.apply(this, [e]);
                }

            });
            return;
        }


        if( tag==='input' && type === 'checkbox'){
            on(el, 'change', function(e){
                
                var name = this.getAttribute('name');
                
                if(name.indexOf('[]') !== -1){

                    var dic = getForm(form);
                    var _name = name.replace('[]', '');
                    form._data[_name] = dic[_name];
                    if(typeof form.change === 'function'){
                        form.change.apply(this, [e]);
                    }
                    return;    
                }



                if(this.checked===true){
                    form._data[name]=this.value;
                    if(typeof form.change === 'function'){
                        form.change.apply(this, [e]);
                    }
                    return;
                }

                form._data[name]=0;
                
                if(typeof form.change === 'function'){
                    form.change.apply(this, [e]);
                }

            });
            return;
        }



        if( tag==='input' && type === 'radio'){
            on(el, 'change', function(e){
            
                var name = this.getAttribute('name');
                form._data[name]=this.value;

                if(typeof form.change === 'function'){
                    form.change.apply(this, [e]);
                }

            });
            return;
        }


        on(el, 'keyup', function(e){
            
            var name = el.getAttribute('name');
            
            form._data[name]=getValue(el);

            if(typeof form.change === 'function'){
                form.change.apply(this, [e]);
            }

        });

    }



    function getValue(el){
        
        var type = el.type.toLowerCase();
        var name = el.getAttribute('name');
        var tag = el.tagName.toLowerCase();

        if(type==='checkbox'){
            if(el.checked === false && el.hasAttribute('noValue') ){
                return el.getAttribute('noValue');
            }else{
                return el.value;
            }
        }

        if(tag === 'input'){
            return el.value;
        }
        

        if(tag === 'select' && type === 'select-one'){
            return el.value;
        }


        if(tag === 'select' && type === 'select-multi'){
            return el.value;   
        }


        if(tag === 'textarea'){
            return el.value;            
        }

        return el.value;
    }



    var __form=function(id){
        
        this.id=id;

        this.map_cache={};

        this.form = document.getElementById(id);

        this._data={};

        this.init();
        //this.binding();
    };


    var fn = __form.prototype;

    fn.init=function(){

        var formData = getForm(this.form);

        for(var prop in formData){
            var value = formData[prop];
            this._data[prop] = value;
            this.define(prop);
        }
    };


    fn.binding=function(){
        var els, el, i, imax
        ;

        els = this.form.elements;

        for (i=0,imax=els.length; i < imax; i++) { 
            el=els[i];
            bind.apply(this, [el]);
        }
    };



    fn.define = function(prop){
        var p ={};
        var that=this;

        p.get=function(){
            return this._data[prop];
        };


        p.set=function(value){
            setForm(this.form, prop, value);
            this._data[prop] = value;
            if( this.map_cache[prop] ){
                storage.set(this.id+'.'+prop, value);
            }
        };


        if(this.getter[prop]){
            p.get = function(){
                return that.getter[prop].apply(that, [prop]);
            };
        }

        if(this.setter[prop]){
            p.set = function(value){
                return that.setter[prop].apply(that, [prop, value]);
            };
        }

        
        Object.defineProperty(this, prop, p);
    };


    fn.submit = function(){
        this.form.submit();
    };



    fn.getAll = function(){
        var data = getForm(this.form);

        for(var prop in data){
            var value = data[prop];

            if(this.map_cache[prop]){
                storage.set(this.id+'.'+prop, value);
            }
        }

        return data;
    };



    fn.set = function(prop, value){
        this.form.setAttribute(prop, value);
    };



    fn.onsubmit = function(fn){
        var that = this;
        on(this.form, 'submit', function(e){
            fn.apply(that, [e]);
        });
    };



    fn.onchange = function(fn){
        this.change = fn;
    };



    fn.queryString = function(){
        var string=[];
        for( var prop in this._data){
            var v = this._data[prop];

            string.push(prop+'='+v);
        }

        return '?'+string.join('&');
    };


    fn.loadSearch = function(queryString){
        if(queryString === this.queryString) return;

        this.loadQueryString(queryString);
    };


    fn.loadQueryString = function(queryString){

        
        var data= parseQuery(queryString);
        
        for(var prop in data){
            
            this[prop] = data[prop];
        }
    };



    fn.cache=function(prop){
        this.map_cache[prop] = 1;

        var key = this.id+'.'+prop;

        if(storage.get(key) !== null){
            this[prop] = storage.get(key);
        }
    };



    fn.getter={};

    fn.setter={};



    window.__form=__form;
}());
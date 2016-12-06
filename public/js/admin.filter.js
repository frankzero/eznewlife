
(function(){
    "use strict";


    var __filter=function(pattern){
        
        this.pattern=pattern;

        this._data={};
        this._data['length']=10;
        this._data['status']='';
        this._data['created_user']='';
        this._data['flag']='';
        this._data['instant']='';
        this._data['search_title']='';
        this._data['search_content']='';
        this._data['search_tag']='';
        this._data['search_id']='';

        this.init();

    };

    var fn = __filter.prototype;


    fn.define = function(prop){
        var p ={};
        var that=this;

        p.get=function(){
            //console.log('getter ', prop);

            if(typeof this._data[prop] === 'undefined') return '';
            return this._data[prop];
        };


        p.set=function(value){
            //console.log('setter ', prop, value);
             var p={};

            p[prop]=value;
            ff(this.pattern).val(p);
            this._data[prop] = value;
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


    fn.init = function(){
        this.getter={};
        this.setter={};
        this.define('category_id');
        this.define('status');
        this.define('flag');
        this.define('instant');
        this.define('created_user');
        this.define('length');
        this.define('search_title');
        this.define('search_content');
        this.define('search_tag');
        this.define('search_id');
    };



    window.__filter=__filter;

}());



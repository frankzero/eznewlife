
EZ.MVVM=new function(){
    var self=this
    ,Self={
        click : function(e){
            var e = e || window.event;
            var dom = e.target || e.srcElement;
            var controller = $(dom).attr('controller');
            if(typeof controller == 'undefined')return;
            console.log('click = '+controller);
            var p = {};
            var attrs = dom.attributes;
            for(var i=0,imax=attrs.length;i<imax;i++){
                var a = attrs[i];
                p[a.name]=a.value;
            }
            Self.exec(p);
            //e.preventDefault(); //阻止默認行為 (form submit)
            e.stopPropagation(); //阻止event flow
        }
        //
        ,execute : function(p){
            p=p||{};
            var controller = p.controller;
            if(!controller){
                console.log('error controller undefined');
                return;
            }
            if(typeof p == 'string'){
                try{
                    p=eval('('+p+')');
                }catch(e){
                    p={}
                }
            }
            var fn = EZ.controller[controller];
            if(!fn){
                //偷懶 預設動作 把model跟 view 找出來使用 直接call
                // 有model就先 call model 再把資料丟給view
                var c = controller.split('.');
                var method = c[c.length-1];
                c.pop();
                c.shift();
                var name = c.join('.');
                if(EZ.model.is_exists(name) && EZ.view.is_exists(name)){
                    var model = EZ.model(name);
                    var view = EZ.view(name);
                    //console.log('default1 name='+name+' , method = '+method);
                    if(typeof model[method] == 'function'){
                        p.callback=view[method];
                        model[method].call(this,p);
                    }else if(typeof view[method] == 'function'){
                        view[method].apply(this,[p]);
                    }
                }else if(EZ.view.is_exists(name)){
                    console.log('default2 name='+name+' , method = '+method);
                    var view = EZ.view(name);
                    if(typeof view[method] == 'function')view[method].apply(this,[p]);
                }
            }else{
                fn.apply(this,[p]);
            }
            /*
            var c = controller.split('.');
            var method = c[c.length-1];
            var type=c[0];
            c.pop();
            c.shift();
            var view = c.join('.');
            //var fn = EZ.view(view)[method];
            var fn = EZ[type](view)[method];
            if(!EZ.isEmpty(fn)) fn.apply(this,[p]);
            */
        }
        ,exec:function(p){
            if(typeof p.controller=='string' && p.controller.indexOf('.show')!=-1 && typeof p.container=='string') EZ.httpstate.push(p);
            Self.execute(p);
        }
        ,buildContainer : function(opt,clearMemory,className){
            var params = '';
            if(typeof opt.container=='string'){
                params = encodeURIComponent(EZ.JSON.encode({controller:opt.controller,container:opt.container}));
                var tmp = document.getElementById(opt.container) || opt.container;
                if(typeof tmp=='string' && tmp.indexOf('containers')!=-1){
                    var s = tmp.split('.');
                    var index = s[s.length-1];
                    s.shift();
                    s.pop();
                    s.pop();
                    tmp = EZ.view(s.join('.')).container(index);
                    if(!tmp){
                        console.log('container string error '+opt.container);
                    }
                }
                opt.container = tmp;
            }
            if(typeof opt.container =='object' && opt.container != null)
            {
                if(typeof opt.container.clearMemory == 'function') opt.container.clearMemory();
                $(opt.container).addClass(className);
                $(opt.container).attr('is_container','1');
                
                //清楚子view 的 memory
                $(opt.container).find('*[is_container=1]').each(function(index){ 
                    console.log(className+' clear 子view');
                    //console.log(index);
                    //console.log($(this).get(0));
                    //var dom = $(this).get(0);
                    var _params = $(this).attr('params');
                    _params = decodeURIComponent(_params);
                    try{
                        _params = eval('('+_params+')');
                        EZ.httpstate.remove(_params);
                    }catch(e){
                        console.log(e);
                    }
                });
                var ds = opt.container.getElementsByTagName('*');
                for(var i=0,imax=ds.length;i<imax;i++)
                {
                    var d = ds[i];
                    $(d).unbind();
                    //if(d.getAttribute('viewer')!='' && typeof d.clearMemory == 'function'){
                    if(d.getAttribute('is_container')!='' && typeof d.clearMemory == 'function'){
                        d.clearMemory();
                    }
                }
                opt.container.clearMemory = clearMemory;
                $(opt.container).attr('params',params);
            }
        }
    };
    return Self;
}();
EZ.exec = EZ.MVVM.exec;
EZ.execute = EZ.MVVM.execute;
EZ.click = EZ.MVVM.click;
EZ.buildContainer = EZ.MVVM.buildContainer;

EZ.httpstate = (new function(){
    var self = this
    ,states = []
    ,states_buff = []
    ,build_query_string = function(g){
        var query = [];
        for(var key in g){
            if(g.hasOwnProperty(key)) query[query.length] = key+'='+g[key] ;
        }
        return query.join('&');
    }
    ,Self = {
        on_load : function(s){
            Self.load(s,true);
        }
        ,load : function(s,shift){
            console.log(s);
            var has_default=false;
            if(typeof s == 'undefined' || s==null){
                return;
            }
            if(typeof s == 'string'){
                s = decodeURIComponent(s);
                try{
                    states = eval('('+s+')');
                    states_buff = eval('('+s+')');
                }catch(e){
                    console.log(e);
                }
            }else{
                states = s;
                states_buff = EZ.clone(s);
            }
            Self.shift();
        }
        ,shift : function(){
            if(EZ.is_array(states_buff) && states_buff.length>0) {
                var p = states_buff.shift();
                //console.log('shift');console.log(p);
                EZ.execute(p);
            }
        }
        ,push : function(p){
            var is_found = false;
            for(var i=0,imax=states.length;i<imax;i++){
                var s = states[i];
                if(s.container==p.container){
                    is_found=true;
                    states[i] = EZ.clone(p);
                }
            }
            if(!is_found){
                states[states.length]=EZ.clone(p);
            }
            var query = build_query_string(Self.remake_query(states));
            var _state = Self.remake_states(states);
            //console.log('query = '+query);console.log(_state);
            history.pushState(_state,'','?'+query);
        }
        ,remove : function(p){
            for(var i=0,imax=states.length;i<imax;i++){
                var s = states[i];
                if(s.container==p.container){
                    states.splice(i,1);
                }
            }
        }
        ,remake_states : function(states){
            return states;
        }
        ,remake_query : function(states){
             //重組
            var g = EZ._GET;
            g.states = EZ.JSON.encode(states);
            return g;
        }
    }
    return Self;
}());
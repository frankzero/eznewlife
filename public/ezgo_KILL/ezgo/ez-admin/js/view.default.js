"use strict";
EZ.view('default',function(){ 
    var opt = {} //設定
    ,DOM = {}
    ,ID = {}
    ,containers={}
    ,appName = EZ.lang("default")
    ,className = 'default'
    //計數器控管 用get 拿到 Timer
    ,Timers = new function(){
        var self = this
        ,cache = []
        ,Self = {
            get : function(o){
                var t = new EZ.Timer(o); //支援參數 callback , block(1000) , direct: 0 ,max:60,min:0 , renderTo:
                cache[cache.length] = t;
                return t;
            }
            ,clear : function(){
                for(var i=0,imax=cache.length;i<imax;i++)
                {
                    var c = cache[i];
                    c.stop();
                }
                cache = [];
            }
        }
        return Self;
    }()
    //清除記憶體 跟 timer
    ,clearMemory = function(){
        if(typeof CollectGarbage == 'function') CollectGarbage();
        $(opt.container).removeClass(className);
        Timers.clear();
    }
    ,complete = function(){
        EZ.httpstate.shift();
    }
    ,draw = function(){
        var data = opt.response.data || {};
        $(opt.container).html('');
    }
    ,init = function(){
       
    }
    ,Self = {
        show:function(o){
            console.log(className+'.show');
            opt = o;
            EZ.buildContainer(opt,clearMemory,className);
            $(opt.container).html('');
            init();
            draw();
            complete();
        }
    };
    return Self;
});

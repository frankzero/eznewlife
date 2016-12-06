"use strict";
EZ.model('default',function(){
    var self = this
    ,appName = EZ.lang('default')
    ,p
    ,callback = function(response){
        try{
            p.response = eval('('+response+')');
            if(!p.response.success && p.response.msg=='sid error'){
                alert('你已經登出');
                location.replace('');
                return;
            }
        }catch(e){
            p.response={success:0,msg:'server response error',data:[]};
        }
        if(typeof p.callback == 'function') p.callback(p);
    }
    ,Self={
        show : function(_p){
            p = _p;
            EZ.api(1,{a:'1'},callback);
        }
    }
    return Self;
});
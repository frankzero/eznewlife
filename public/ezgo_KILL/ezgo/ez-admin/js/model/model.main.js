"use strict";EZ.model('main',function(){    var self = this    ,appName = EZ.lang('main')    ,p    ,callback = function(response){        try{            p.response = eval('('+response+')');            if(!p.response.success && p.response.msg=='sid error'){                alert('你已經登出');                location.replace('');                return;            }        }catch(e){            p.response={success:0,msg:'server response error',data:[]};        }        EZ.loadingbar.hide();        if(typeof p.callback == 'function') p.callback(p);    }    ,Self={        show : function(_p){            p = _p;            EZ.api('get_user_info',{},callback);            //p.callback(p);        }    }    return Self;});
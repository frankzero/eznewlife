"use strict";
EZ.view('main',function(){
    var opt = {} //設定
    ,DOM = {}
    ,ID = {
        container1:EZ.id()
        ,container2:EZ.id()
    }
    ,containers={}
    ,appName = EZ.lang("主畫面")
    ,className = 'main'
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
        //console.log(className+' shift');
        EZ.httpstate.shift();
    }
    ,draw = function(){
        var data = opt.response.data;
        var h = '';
        h = '\
<nav id="nav" class="main-nav" >\
<a controller="view.add.show" container="content">新增</a>\
<a controller="view.edit.show" container="content">編輯</a>\
<a>廣告</a>\
<a controller="view.logout.show">登出</a>\
</nav>\
\
<div id="page-wrap" class="page-wrap">\
   <header id="header" class="main-header" >\
      <!--a class="open-menu" >☰</a-->\
\
    <div id="image_form">\
        <form id="form3" method="POST" action="ajax_fileupload.php">\
            <input type="file" id="fileselect" multiple="true" accept="image/*">\
            <div class="image_upload">\
                <div id="dropbox" class="dropbox" style="padding:0;line-height:40px;color:#fff;border:2px dashed #fff;">拖曳圖片</div>\
                <div id="upload_progress" class="upload-progress" style="position:absolute;left:300px;top:0;background-color:#fff;"></div>\
            </div>\
        </form>\
    </div>\
\
      <h1 id="main-h1">&nbsp;</h1>\
   </header>\
   <div id="content" class="content">\
\
   </div>\
</div>\
';
        $(opt.container).html(h);
        $(opt.container).unbind();
        removeMask();
        document.getElementById('main').style.display='block';
        $(opt.container).click(EZ.click);
        $('#nav').addClass('menu-expand');
        $('#page-wrap').addClass('page-wrap-expand');
        $('#header').addClass('main-header-expand');
        
        // 拖曳圖片
        var box = new EZ.dropbox();
        box.render({
            id:'dropbox'
            ,progress:'upload_progress'
            ,cls:'dropbox'
            ,action:'ajax_fileupload.php'
            ,callback:function(response,complete_message){
                //var r = EZ.json_decode(response); 
                $('#form1').find('input[name=image]').val(response);
                $('#'+complete_message).html('<input type="text" value="'+response+'" style="width:300px;">');
                
            }
        });
        document.getElementById('fileselect').addEventListener("change", box.handleFiles, false);
        //EZ.exec({controller:'view.add.show',container:'content'});
    }
    ,Self = {
        show:function(o){
            console.log(className+'.show');
            opt = o;
            EZ.buildContainer(opt,clearMemory,className);
            $(opt.container).html('');
            draw();
            complete();
        }
        ,clear : clearMemory
        ,container : function(index){
            return containers[index];
        }
    };
    return Self;
});

(function(){

    function html(){
        var h='';
        h+='<div class="site-wrapper">';

            h+='<div class="site-wrapper-inner">';

                h+='<div class="cover-container">';

                    h+='<div class="inner cover"> <div class="box box-warning direct-chat direct-chat-warning">'
                        '';
                        h+='' +
                            '<img class="adultimg" src="/images/comic-18.png">';
                        h+='<img  src="/images/comic_logo.png" id="logo">';

                        h+='<p  id="adult-p" class="lead"><strong>本網站已依網站內容分級規定處理<br>'
                             +'警告︰您即將進入之看板內容需滿十八歲方可瀏覽。</strong><br/><br/>'
                            +'<small>根據「電腦網路內容分級處理辦法」第六條第三款規定，本網站已於各限制級網頁依照台灣網站分級推廣基金會之規定標示。 ' +
                            '<br>若您尚未年滿十八歲，請點選離開。'
                            +'</small></p>';
                        h+='<div class="row">';
                            h+='<div class="col-lg-6 col-md-12">';

                                h+='<button type="button" class="agree_btn btn btn-block btn-lg  btn-danger bg-maroon-gradient "  >我已成年，進入網站</button>';




                            h+='</div>';
                            h+='<div class="col-lg-6 col-md-12">';
                            h+='<a href="'+site_leave_url+'" class="disagree_btn btn btn-block btn-lg   bg-navy">帶我離開</a></div></div></div>';
                        h+='</div>';
                    h+='</div>';

                    h+='<div class="mastfoot center " style="width: 100%">';
                        h+='<div class="inner">';
                            h+='<p>Copyright &copy; '+title+' 2016</p>';
                        h+='</div>';
                    h+='';

                h+='</div>';

            h+='</div>';

        h+='</div>';

        return h;
    }


    function setCookie_(cookieName, cookieValue, nHour){
        var today = new Date();
         var expire = new Date();
         if(typeof nHour ==='undefined') nHour=4;
         expire.setTime(today.getTime() + 3600000*nHour);
         document.cookie = cookieName+"="+escape(cookieValue)
                         + ";expires="+expire.toGMTString();
    }

    function setCookie(name, value){
        var expires;

        var Path='/';

        var second = 86400; //24 hour
        
        var t = new Date().getTime();
        
        
        //expires = EZ.Date().second(second).dateObject.toGMTString();
        expires = new Date(t + second*1000).toGMTString();
        
        
        
        
        if(expires == 'Invalid Date'){
            console.log('cookie Invalid Date '+name+' '+value+' '+second );
        }
        document.cookie = name + "=" + value + ";Path="+Path+";" + "expires="+expires;
    }

    function getCookie(name){
        name = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) 
        {
            var c = ca[i].trim();
            if (c.indexOf(name) === 0) return c.substring(name.length,c.length);
        }
        return "";
    }


    function adultCheck(){

        if(!this.isAdult()){
            this.openPanel();
        }
    }


    adultCheck.prototype.isAdult = function(){
        if(!getCookie('agreeAdult')){
            return false;
        }

        return true;
    };


    adultCheck.prototype.agree = function(){
        
        setCookie('agreeAdult', '1');

        this.closePanel();
    };


    adultCheck.prototype.openPanel = function(){

        var that = this;
        var div = document.createElement('div');

        this.div = div;

        div.innerHTML = html();
        div.className = 'adultPanel';

        console.log(div.querySelector('.agree_btn'));
        
        div.querySelector('.agree_btn').onclick=function(e){
            console.log('agree');
            that.agree();
        };

        //$('.agree_btn', div).click(function(e){
            
        //});

        //$('.disagree_btn', div).click(function(e){
            //console.log('disagree');
        //});

        document.body.appendChild(div);

    };


    adultCheck.prototype.closePanel = function(){
        if(this.div.parentNode){
            this.div.parentNode.removeChild(this.div);
        }
    };


    function check(){
        new adultCheck();
    }


    window.__adultCheck = adultCheck;

    window.adultCheck=check;
}());

adultCheck();
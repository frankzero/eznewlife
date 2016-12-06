
(function(){

    function html(){
        var h='';
        h+='<div class="site-wrapper">';

            h+='<div class="site-wrapper-inner">';

                h+='<div class="cover-container">';

                    h+='<div class="inner cover">';
                        h+='<img class="adultimg" src="/images/adult.png">';
                        h+='<h1 id="adult-h1" class="cover-heading" >'+title+'</h1>';
                        h+='<p  id="adult-p" class="lead">您接下前往的分類為成人類別，含有情色資訊的網站，您必須年滿18歲或達到當地法律許可之法定年齡才可以瀏覽本站內容，如果您尚未成年，請立即離開！</p>';
                        h+='<div class="row">';
                            h+='<div class="col-lg-6 col-md-12">';

                                h+='<button type="button" class="agree_btn btn btn-block btn-lg btn-flat btn-success "  ><span class="fa fa-heart"></span>我已18歲，進入參觀</button>';




                            h+='</div>';
                            h+='<div class="col-lg-6 col-md-12">';
                            h+='<a href="'+site_leave_url+'" class="disagree_btn btn btn-block btn-lg btn-flat btn-danger"><span class="fa fa-ban"></span>我未滿18歲，禁止禁入</a></div>';
                        h+='</div>';
                    h+='</div>';

                    h+='<div class="mastfoot">';
                        h+='<div class="inner">';
                            h+='<p>Copyright &copy; '+title+' 2016</p>';
                        h+='</div>';
                    h+='</div>';

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
       // your_key_name=your_key_value;domain=.example.com;expires
        document.cookie = name + "=" + value + ";Path="+Path+";" + "expires="+expires+";";
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
      //  console.log(div);
     //  $(".adv").addClass("hidden");
        document.body.appendChild(div);

    };


    adultCheck.prototype.closePanel = function(){
        if(this.div.parentNode){
            this.div.parentNode.removeChild(this.div);
          //  $(".adv").removeClass("hidden");
        }
    };


    function check(){
        new adultCheck();
    }


    window.__adultCheck = adultCheck;

    window.adultCheck=check;
}());

adultCheck();
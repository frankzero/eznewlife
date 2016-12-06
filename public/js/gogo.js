(function(){
    "use strict";

    function rand (min, max) {
        return Math.round(Math.random() * (max - min) + min);
    };

    var setCookie=function(name, value, second, Path){
        var expires;
        var d;

        if(typeof second === 'undefined') second = 3600*24*30*1000; //30天 
        if(typeof Path === 'undefined')  Path = '/';

        d = new Date( new Date().getTime() + second*1000 );

        expires = d.toGMTString();
        
        if(expires == 'Invalid Date'){
            console.log('cookie Invalid Date '+name+' '+value+' '+second );
        }
        document.cookie = name + "=" + value + ";Path="+Path+";" + "expires="+expires;
    };

    var getCookie=function(name){
        name = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) 
        {
            var c = ca[i].trim();
            if (c.indexOf(name) === 0) return c.substring(name.length,c.length);
        }
        return "";
    };

    var deleteCookie=function(name, _global){
       setCookie(name, '', -1, ( _global ? '/' : '' )); 
    }


    var moveScroll = function(pixel){
        var p = document.body.scrollTop+pixel;
        // console.log(document.body.scrollTop);
        // console.log('move p ', p, pixel);
        window.scrollTo(0,p);
    };

    // =====================================================

    var __gogo=function(ffb, rffb, ffb_time, ad_window_time){
        this._data=[];
        this.rate=[];
        this.ffb=ffb;
        this.rffb=rffb;
        this.ffb_time=ffb_time;
        this.ad_window_time=ad_window_time;
        this.delayTime=0;
    };

    var fn = __gogo.prototype;

    fn.set=function(rate, pixel, name){

        if(typeof name === 'undefined') name='';

        this._data.push({rate:rate, pixel:pixel, name:name});
    };


    // 75,100|40,-350|0,-50
    fn.load = function(str){
        str = str.trim();
        var i, imax, s, ss, g, gg;

        ss = str.split('|');

        for (i=0,imax=ss.length; i < imax; i++) { 
            s=ss[i];

            if(s.indexOf(",") === -1) continue;
            //console.log(s);
            g = s.split(",");

            this.set(g[0]-0, g[1]-0);

        }
    };


    fn.delay = function(delayTime){
        this.delayTime=delayTime;
    };




    fn.make_rate=function(){
        var s=0, e=0, i, imax, d, tmp;

        for (i=0,imax=this._data.length; i < imax; i++) { 
            d=this._data[i];
            s = e;
            e = s+d.rate;
            this.rate.push({start:s, end:e , data:d});
        }

    };




    fn.run_ffb = function(){
        
        var nexttime, now, ffb_time;

        if(this.ffb_time==0) return true;

        ffb_time = getCookie('ffb_time');

        if( ffb_time === ''){
            nexttime = new Date().getTime();
            nexttime = nexttime + this.ffb_time*3600000;
            setCookie('ffb_time', nexttime);
            return true;
        }


        ffb_time = getCookie('ffb_time') - 0;

        now = new Date().getTime();

        if(now > ffb_time){
            nexttime = new Date().getTime();
            nexttime = nexttime + this.ffb_time*3600000;
            setCookie('ffb_time', nexttime);
            return true;
        }


        return false;

    };




    fn.run_ad_window = function(){
        var ad_window_time, nexttime, now;
        // debugger;
        if(this.ad_window_time==0) return true;
        
        if(this.ad_window_time==-1) return false;

        ad_window_time = getCookie('ad_window_time');
        

        if( ad_window_time === ''){
            nexttime = new Date().getTime();
            nexttime = nexttime + this.ad_window_time*3600000;
            setCookie('ad_window_time', nexttime);
            return true;
        }


        ad_window_time = getCookie('ad_window_time') - 0;

        now = new Date().getTime();

        if(now > ad_window_time){
            nexttime = new Date().getTime();
            nexttime = nexttime + this.ad_window_time*3600000;
            setCookie('ad_window_time', nexttime);
            return true;
        }


        return false;
    };


    fn.execute_ffb = function(){
        var i, imax, d, s, e, rate, ffb_time, ad_window_time, rate, that;
        that=this;

        if(!that.ffb){
            console.log('not ffb');
            return false;
        }


        rate=rand(1,100);
        if(rate > that.rffb){
            console.log('not rffb', that.rffb, rate);
            return false;
        }



        if(that.run_ffb()){
                
            rate = rand(1, 100);

            for (i=0,imax=that.rate.length; i < imax; i++) { 
                d=that.rate[i];
                s = d.start;
                e = d.end;

                console.log(' === ', i,  rate, s, e, d.data.pixel);
                if( rate > s && rate <= e){

                    if(typeof d.data.pixel === 'function'){
                        d.data.pixel.call();
                        return true;
                    }


                    moveScroll(d.data.pixel);
                    return true;
                }
            }

            console.log('m n');
            return false;
        }


        return false;
    };



    fn.execute_ad_window=function(){
        var that=this;

        if(that.run_ad_window()){
            console.log('run ad window');
            if(typeof ad_show === 'function') ad_show();
            return true;
        }
        

        return false;


    };


    fn.execute = function(){
        var that=this;

        this.make_rate();

        setTimeout(function(){
            var i, imax, d, s, e, rate, ffb_time, ad_window_time, rate, bool;

            if(that.execute_ffb() ){

                return ;
            }



            if(that.execute_ad_window()){

                return;
            }






            console.log('nnn')


        }, that.delayTime);

        

    };


    window.__gogo=__gogo;

    

}());
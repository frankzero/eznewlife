<?php 

namespace www_push01_net;


use _scraping\get_html as ab;
use __req;


class get_html extends ab{

    function call($url){

        $this->url = $url;

        
        if(strpos($url, '?') ===false){
            $myurl = $url.'?agree=1';
        }else if(strpos($url, 'agree') ===false){
            $myurl = $url.'&agree=1';
        }else{
            $myurl = $url;
        }


        $req = new __req($myurl);

        $req->setHeader('Referer', $this->url);

        return $this->handle_html($req->response());
    }

}
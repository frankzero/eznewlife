<?php 

class scraping_fun01_cc extends ab_scraping{

    public function __construct($url){
        parent::__construct($url);
    }


    public function content(){

        $url = $this->url;
        
        if(strpos($url, '?') ===false){
            $myurl = $url.'?agree=1';
        }else if(strpos($url, 'agree') ===false){
            $myurl = $url.'&agree=1';
        }else{
            $myurl = $url;
        }


        $req = new __req($myurl);

        $req->setHeader('Referer', $this->url);

        return $this->handle_response($req->response());
        
    }
}
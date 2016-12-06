<?php 

class scraping_www_muse01_com extends ab_scraping{
    
    public function __construct($url){
        parent::__construct($url);
    }


    public function content(){

        $contents = file_get_contents($this->url);

        $spider='';

        //echo $contents;
        $tmp = explode('spider=', $contents);
        $tmp = $tmp[1];
        $tmp = explode('";', $tmp);
        $spider=$tmp[0];

        $req = new __req($this->url);

        $req->setHeader('Referer', $this->url);

        $req->setCookie('_ga', 'GA1.2.1493165931.1453989559');
        $req->setCookie('_gat', '1');
        $req->setCookie('__cfduid', 'dec2b3c0791aebc8c4f2325b449d4b66a1453989557');
        $req->setCookie('PHPSESSID', '6emmk7l3p1jg3f8d1f90vhkip6');
        $req->setCookie('spider', $spider);

        return $this->handle_response($req->response());

    }
}
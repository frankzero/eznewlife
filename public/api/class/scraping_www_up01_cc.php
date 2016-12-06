<?php 

class scraping_www_up01_cc extends ab_scraping{

    public function __construct($url){
        parent::__construct($url);
    }


    public function content(){

        $url = $this->url;
        $forbid_url = 'http://www.up01.cc/forbid.html';
        

                
        $req = new __request($url);
        //$req->clearCookie();

        $r = $req->response();

        //print_r($req->http_response_header);

        //print_r($req->cookie);

        //echo $r;

        $req2 = new __request($forbid_url);

        $req2->cookie = $req->cookie;

        //print_r($req2->cookie);

        $r2 = $req2->response();
        //echo $r2;

        $req3 = new __request($url);
        $req3->cookie = $req2->cookie;
        $req3->setHeader('Referer', $forbid_url);
        $req3->setCookie('over18', '1');

        $req3->response();


        $u = new __url($url);

        $u->set('agree', '1');

        $req4 = new __request($u->toString());
        $req4->cookie = $req3->cookie;
        $req4->setHeader('Referer', $url);

        $response = $req4->response();

        //echo $response;


        return $this->handle_response($response);
        
    }
}
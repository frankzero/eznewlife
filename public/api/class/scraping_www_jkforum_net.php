<?php 

class scraping_www_jkforum_net extends ab_scraping{


    public function __construct($url){
        parent::__construct($url);
    }



    public function content(){
        $req = new __req($this->url);

        $r = $this->handle_response($req->response());

        
        //$r=mb_convert_encoding($r,"UTF-8", "big5"); 
        //$r=iconv("gb2312","UTF-8",$r); 

        //echo $r;exit;

        return $r;
    }

}
<?php 

class scraping_bbs_tiexue_net extends ab_scraping{


    public function content(){

        $req = new __req($this->url);

        $r = $req->response();
        $r = gzdecode($r);
        $r = iconv("gb2312","UTF-8", $r);

        return $this->handle_response($r);
        
    }
}

<?php 

class scraping_share_youthwant_com_tw extends ab_scraping{


    public function content(){

        $url = $this->url;
        $req = new __req($url);

        $req->setHeader('Referer', 'http://iguang.tw/t/goto?url='.$url);
        $req->setHeader('Host', 'share.youthwant.com.tw');

        $r = $req->response();

        $r=mb_convert_encoding($r,"UTF-8", "big5"); 
        
        $this->handle_response($r);

        

        return $r;
        
    }
}
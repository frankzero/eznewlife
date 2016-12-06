<?php 

class scraping_weibo_com extends ab_scraping{


    public function content(){

        $req = new __req($this->url);

        //$req->setHeader('Referer', $this->url);

        // $req->setCookie('a', '1');
        // $req->setCookie('b', '2');

        $req->setHeader('Cookie', 'SINAGLOBAL=7118898783810.437.1374456290950; km_ai=S2AONIPMIZ%2FVdBEeKJ33c2k34ZM%3D; km_uq=; gads=ID=747f7a8ca9d0d689:T=1455586807:S=ALNI_Ma4-RRs7FDdLxIJUKe-Dx5-DfUpJw; UOR=www.facebook.com,weibo.com,blog.csdn.net; YF-Page-G0=0acee381afd48776ab7a56bd67c2e7ac; s_tentry=-; Apache=506642364301.72595.1460341983784; ULV=1460341984594:16:1:1:506642364301.72595.1460341983784:1455780609880; YF-Ugrow-G0=56862bac2f6bf97368b95873bc687eef; YF-V5-G0=c99031715427fe982b79bf287ae448f6; wb_publish_fist100_5896457548=1; SUHB=0U1Jqy0XVZOjsN; myuid=5896457548; SUB=_2AkMgV8ZpdcPhrAZZkf4RyWznaIVJygj8sdD4Mk_bEicKLix_7DxnqiRqtUF8GKyg2US7sR0bQfWXc8BNr1zTQqpGobq9TVXX; SUBP=0033WrSXqPxfM72wWs9jqgMF55529P9D9W5AEpWb39ppm9.b1anEENs25JpVho571hnc1KzpS0-X1Kzf; login_sid_t=2250990a8912baea6927401d41115574; appkey=; WBtopGlobal_register_version=ab9111fb56d70a2b; gat=1; _ga=GA1.2.1834223729.1405310984; crtg_rta=; a=2');

        //$u->setHeader('Referer', 'http://weibo.com/yinchxi?refer_flag=1005050010_&is_hot=1');

        $req->setHeader('Cache-Control', 'no-cache, must-revalidate');

        $req->setHeader('Upgrade-Insecure-Requests', '1');
        $req->setHeader('Connection', 'close');

        $req->setHeader('User-Agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36');

        return $this->handle_response($req->response());
        
    }
}
<?php 

namespace ck101_com;


use _scraping\get_html as ab;


class get_html extends ab{



    function call($url){
        //$url = 'http://ck101.com/thread-3558024-1-1.html?ref=banner';

        $cookiefile=tempnam(sys_get_temp_dir(), md5(microtime()));
        //$cookiefile= __DIR__.'/../storage/cookie.txt';
        //file_put_contents($cookiefile, '');


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, '1');
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
        $r = curl_exec($ch);
        curl_close($ch);
        

        if(strpos($r, 'challenge-form') === false){
            unlink($cookiefile);

            return $this->handle_html($r);
        }

        exit;
    }


}
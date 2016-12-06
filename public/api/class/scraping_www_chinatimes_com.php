<?php 

class scraping_www_chinatimes_com extends ab_scraping{

    public function __construct($url){
        parent::__construct($url);
    }


    public function content(){

        $url = $this->url;
        
        $r = file_get_contents($url);

        return $this->handle_response($r);
        
    }
}
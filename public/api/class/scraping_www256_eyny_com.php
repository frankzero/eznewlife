<?php 

class scraping_www256_eyny_com extends ab_scraping{

    public function __construct($url){
        parent::__construct($url);
    }


    public function content(){

        
        $eyny = new get_html_eyny_com($this->url);

        return $this->handle_response($eyny->response());
        
    }
}
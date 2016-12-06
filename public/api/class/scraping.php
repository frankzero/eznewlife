<?php 

class scraping extends ab_scraping{


    public function __construct($url){
        parent::__construct($url);
    }



    public function content(){
        $req = new __req($this->url);

        return $this->handle_response($req->response());
    }

}
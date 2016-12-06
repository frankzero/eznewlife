<?php 

class find_sitename{

    protected $url;


    public function __construct($url){
        $this->url=$url;

        $this->host = parse_url($url,PHP_URL_HOST);

        $scheme = parse_url($url,PHP_URL_SCHEME);

        $this->domain = $scheme.'://'.$this->host;


    }


    public function getSiteName(){

        $req = new __req($this->domain);

        $html = $req->response();

        $query = file_get_html( $html );

        $doms = $query->find('title');

        $title = $doms[0]->innertext;

        $title=$this->remove_script($title);
        $title = preg_replace("/<img[^>]+\>/i", "", $title); 
        $title = html_entity_decode($title);


        return $title;
    }



    private function remove_script($html){
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        $html = preg_replace('#<ins(.*?)>(.*?)</ins>#is', '', $html);
        return $html;
    }

}
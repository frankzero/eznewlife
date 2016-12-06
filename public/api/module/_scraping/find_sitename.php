<?php 

namespace _scraping;

use \__req;

abstract class find_sitename{



    function call($url){
        $host = parse_url($url,PHP_URL_HOST);
        $scheme = parse_url($url,PHP_URL_SCHEME);
        $domain = $scheme.'://'.$host;


        $req = new __req($domain);

        $html = $req->response();

        $query = file_get_html( $html );

        $doms = $query->find('title');

        $title = $doms[0]->innertext;

        $title=$this->remove_script($title);
        $title = preg_replace("/<img[^>]+\>/i", "", $title); 
        $title = html_entity_decode($title);


        return $title;
    }






    protected function remove_script($html){
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        $html = preg_replace('#<ins(.*?)>(.*?)</ins>#is', '', $html);
        return $html;
    }
}
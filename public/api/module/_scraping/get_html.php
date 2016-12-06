<?php 
namespace _scraping;

use __req;

abstract class get_html{


    protected $url;


    function call($url){

        $this->url = $url;

        $req = new __req($this->url);

        $html = $req->response();

        $html = $this->handle_html($html);

        return $html;

    }




    protected function handle_html($html){
        $html = preg_replace('/<img([^>]+)(class\s*=\s*"[^"]+")([^>]+)>/', '<img $1 $3 >', $html);

        $html = $this->remove_tag($html, 'div.fb-like');
        $html = $this->remove_tag($html, 'div.fb-page');
        $html = $this->remove_tag($html, 'div.sitemaji_banner');


        return $html;
    }




    private function remove_tag($html, $selector){
        $query = file_get_html($html);

        $els = $query->find($selector);

        for ($i=0,$imax=count($els); $i < $imax; $i++) { 
            $el=$els[$i];
            $el->outertext='';
        }

        $html = $query.'';


        return $html;
    }
}
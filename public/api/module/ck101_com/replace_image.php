<?php 

namespace ck101_com;


use _scraping\replace_image as ab;
use simple_html_dom_node;

class replace_image extends ab{


    protected function handle_image_src(simple_html_dom_node $img){
        
        $url = $img->getAttribute('file');

        if(!$url){
            $url = $img->getAttribute('src');
        }

        if(strpos($url, '.gif.jpg') !== false){
            $url = str_replace('.gif.jpg', '.gif', $url);
        }

        $url = $this->make_image_url($url);

        return $url;
    }
    

}
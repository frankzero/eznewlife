<?php 

class replacer extends ab_replacer{


   

    protected function handle_image_src(simple_html_dom_node $img){
        $url = $img->getAttribute('src');

        if(!$url) $url = $img->getAttribute('data-src');

        $url = $this->make_image_url($url);

        return $url;
    }



    protected function make_image_url($path){
        if(strpos($path, 'http') !== false) return $path;
        if(strpos($path, '/') !== 0) $path = '/'.$path;
        return $this->domain.$path;
    }

}
<?php 

class replacer_ck101_com extends replacer{

  

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
<?php 

class replacer_news_gamme_com_tw extends replacer{

  

    protected function handle_image_src(simple_html_dom_node $img){
        
        $url = $img->getAttribute('data-original');

        if(!$url) $url = $img->getAttribute('src');
        
        $url = $this->make_image_url($url);

        return $url;
    }

}
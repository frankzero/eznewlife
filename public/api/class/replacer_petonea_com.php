<?php 

class replacer_petonea_com extends replacer{

    protected function handle_image_src(simple_html_dom_node $img){
        $url = $img->getAttribute('adonis-src');

        $url = '/file'.$url;

        $url = $this->make_image_url($url);

        return $url;
    }

}
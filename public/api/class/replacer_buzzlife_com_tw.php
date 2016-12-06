<?php 

class replacer_buzzlife_com_tw extends replacer{

    


    protected function handle_image_src(simple_html_dom_node $img){
        $url = $img->getAttribute('data-src');

        $url = $this->make_image_url($url);

        return $url;
    }

}
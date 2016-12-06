<?php 

class find_title_www_bunnygo_net extends find_title{

    public function getTitle(){

        $selector = '.header .txt16';
        $doms = $this->query->find($selector);

        if(isset($doms[0])){
            return $doms[0]->innertext;
        }


        $doms = $this->query->find($this->selector);
        return $doms[0]->innertext;

        $doms = $this->query->find($this->selector);
        $title = $doms[0]->innertext;
        $title=$this->remove_script($title);
        $title = preg_replace("/<img[^>]+\>/i", "", $title); 
        $title = html_entity_decode($title);
        return $title;
    }
}
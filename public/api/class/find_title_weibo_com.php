<?php 

class find_title_weibo_com extends find_title{

    public function getTitle(){
        return '';
        $doms = $this->query->find($this->selector);
        $title = $doms[0]->innertext;
        $title=$this->remove_script($title);
        $title = preg_replace("/<img[^>]+\>/i", "", $title); 
        $title = html_entity_decode($title);
        return $title;
    }
}
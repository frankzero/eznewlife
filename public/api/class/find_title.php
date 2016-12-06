<?php 

class find_title{


    public function __construct($html, $selector){
        $this->html = $html;
        $this->selector = $selector;
        $this->query = file_get_html($html);
    }


    public function getTitle(){
        $doms = $this->query->find($this->selector);
        $title = $doms[0]->innertext;
        $title=$this->remove_script($title);
        $title = preg_replace("/<img[^>]+\>/i", "", $title); 
        $title = html_entity_decode($title);
        $title=trim($title);
        return $title;
    }



    protected function remove_script($html){
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        $html = preg_replace('#<ins(.*?)>(.*?)</ins>#is', '', $html);

        return $html;
    }
}
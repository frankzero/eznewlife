<?php 

class find_content{

    protected $html;
    protected $selector;

    public function __construct($html, $selector){
        $this->html = $html;
        $this->selector = $selector;
        $this->query = file_get_html($html);
    }


    public function content(){
        $content = $this->getContent();

        $content = $this->remove_danger_tag($content);

        return $content;
    }



    protected function getContent(){
        $doms = $this->query->find($this->selector);

        $el = $doms[0]->find('.hidecontent');

        if(isset($el[0])){
            $el[0]->removeAttribute('style');
        }

        $html = $doms[0];

        return $html;
    }



    protected function remove_danger_tag($html){

        //$html=strip_tags($html,'<div><p><a>')
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        $html = preg_replace('#<ins(.*?)>(.*?)</ins>#is', '', $html);

        // teepr 拿掉 srcset
        $html = preg_replace("#(<img[^>]+)srcset=\"[^\"]+\"([^>]+>)#", '$1 $2', $html);

        //拿掉 html 註解 
        $html = preg_replace("#<!--(?!<!)[^\[>][\s\S]*?-->#", '$1 $2', $html);
        
        return $html;
    }
}
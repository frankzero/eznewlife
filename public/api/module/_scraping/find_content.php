<?php 

namespace _scraping;


abstract class find_content{


    protected $html;
    protected $selector;


    function call($html, $selector){

        $this->html = $html;
        $this->selector = $selector;
        
        $query = file_get_html($html);
        
        $content = $this->getContent($query, $selector);

        $content = $this->remove_danger_tag($content);

        $content = $this->handle_string($content);

        return $content;

    }






    protected function handle_string($content){

        $content = str_replace('sitemaji_banner', 'aaaaaa', $content);
        $content = str_replace('http://vlog.xuite.net/embed', 'https://vlog.xuite.net/embed', $content);
        $content = str_replace('http://www.youtube.com/embed', 'https://www.youtube.com/embed', $content);

        return $content;
    }
    





    protected function getContent($query, $selector){
        $doms = $query->find($selector);

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
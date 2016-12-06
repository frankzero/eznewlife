<?php 

class find_content_ck101_com extends find_content{
    
    protected function getContent(){
        $html = $this->html;

        $prefix='<article>';
        $tail='<\\/article>';
        preg_match('/'.$prefix.'([\s\S.]*)'.$tail.'/iU',$html, $match);


        $html =$match[0];


        $prefix='<td';
        $tail='<\\/td>';
        preg_match('/'.$prefix.'([\s\S.]*)'.$tail.'/iU',$html, $match);

        //print_r($match);

        $html = $match[0];

        return $html;
    }
}
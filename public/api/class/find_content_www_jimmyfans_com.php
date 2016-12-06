<?php 

class find_content_www_jimmyfans_com extends find_content{
    
    protected function getContent(){

        $html='';

        $query = file_get_html($this->html);

        $doms = $query->find('.ads_mid');

        for ($i=0,$imax=count($doms); $i < $imax; $i++) { 
            $d=$doms[$i];
            $d->outertext = '';
        }


        $html.=$this->get($query, 'h1.objectTitle');
        $html.=$this->get($query, '#divPreview');
        $html.=$this->get($query, '.div_object_desc');



        return $html;
    }


    private function get($query, $selector){
        $doms = $query->find($selector);

        return $doms[0]->outertext;
    }
}
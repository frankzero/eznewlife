<?php 

class find_content_www256_eyny_com extends find_content{
    
    protected function getContent(){

        $html = parent::getContent();
        $query = file_get_html($html);

        $imgs = $query->find('img');

        $h = '';

        for ($i=0,$imax=count($imgs); $i < $imax; $i++) { 
            $img=$imgs[$i];
            $h.='<p><img src="'.$img->getAttribute('src').'" \></p>';
        }


        return $h;

    }
}
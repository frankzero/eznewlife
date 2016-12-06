<?php 

class find_content_laughbombclub_com extends find_content{
    
    protected function getContent(){

        $html = parent::getContent();
        
        $query = file_get_html($html);

        $doms = $query->find('.visible-xs');

        for ($i=0,$imax=count($doms); $i < $imax; $i++) { 
            $d=$doms[$i];
            $d->outertext='';
        }

        return $query.'';
    }
}
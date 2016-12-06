<?php 

class find_content_www_jkforum_net extends find_content{

    protected function getContent(){
        $doms = $this->query->find($this->selector);

        $dom = $doms[0];

        $els = $dom->find('div.tip_4');

        for ($i=0,$imax=count($els); $i < $imax; $i++) { 
            $el=$els[$i];
            $el->outertext='';
        }


        $el = $dom->find('h3.psth');
        if($el) $el[0]->outertext='';

        $el = $dom->find('dl.rate');
        if($el) $el[0]->outertext='';


        $el = $dom->find('div.modact');
        if($el) $el[0]->outertext='';


        $html = $dom.'';

        return $html;
    }

}
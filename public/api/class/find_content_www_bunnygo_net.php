<?php 

class find_content_www_bunnygo_net extends find_content{
    
    protected function getContent(){

        $doms = $this->query->find($this->selector);

        if(isset($doms[0])){
            return $doms[0]->innertext;
        }



        $html = '';

        //$doms = $this->query->find('.fw_video');
        $doms = $this->query->find('.container .col1');

        $html .= '<div>'.$doms[0]->innertext.'</div>';



        return $html;
        

    }
}
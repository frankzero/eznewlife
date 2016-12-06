<?php 

class handle_string_petonea_com extends handle_string{
    
    public function response(){
        
        $this->remove_img_source();

        return $this->html;

    }


    private function remove_img_source(){
        
        $query = file_get_html($this->html);

        $els = $query->find('div.img_source');

        for ($i=0,$imax=count($els); $i < $imax; $i++) { 
            $el=$els[$i];
            $el->outertext='';
        }

        $this->html = $query.'';

    }


}
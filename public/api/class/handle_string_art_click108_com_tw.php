<?php 

class handle_string_art_click108_com_tw extends handle_string{
    public function response(){
        
        $this->remove_page_bar();
        $this->remove_ad();

        return $this->html;

    }


    private function remove_page_bar(){
        
        $query = file_get_html($this->html);

        $els = $query->find('div.PAGE');

        for ($i=0,$imax=count($els); $i < $imax; $i++) { 
            $el=$els[$i];
            $el->outertext='';
        }

        $this->html = $query.'';

    }



    private function remove_ad(){

        $query = file_get_html($this->html);

        $els = $query->find('center');

        for ($i=0,$imax=count($els); $i < $imax; $i++) { 
            $el=$els[$i];
            $el->outertext='';
        }

        $this->html = $query.'';
        
    }
}
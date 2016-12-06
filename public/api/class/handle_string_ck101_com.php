<?php 

class handle_string_ck101_com extends handle_string{

    public function response(){
        $this->_youtube();
        $this->_instagram();
        //$this->_table();
        return $this->html;

    }


    private function _youtube(){
        
        if(strpos($this->html, 'v.ck101.com/youtube.html') !== false){
            $this->html = str_replace('v.ck101.com/youtube.html#', 'www.youtube.com/embed/', $this->html);            
        }
    }


    private function _instagram(){
        $query = file_get_html($this->html);

        $bs = $query->find('blockquote.instagram-media');

        for ($i=0,$imax=count($bs); $i < $imax; $i++) { 
            $b = $bs[$i];

            $a = $b->find('a')[0];
            
            $href = $a->getAttribute('href');

            $instagram_id = $this->instagram_id($href);

            $src = 'https://www.instagram.com/p/'.$instagram_id.'/embed/captioned/?v=6';
            $b->outertext='<p>'.$this->makeIframe($src).'</p>';
            
            //<iframe class="instagram-media instagram-media-rendered" id="instagram-embed-0" src="https://www.instagram.com/p/-Xz2cCDmOr/embed/captioned/?v=6" allowtransparency="true" frameborder="0" height="1036" data-instgrm-payload-id="instagram-media-payload-0" scrolling="no" style="border: 0px; margin: 1px; max-width: 1000px; width: calc(100% - 2px); border-radius: 4px; box-shadow: rgba(0, 0, 0, 0.498039) 0px 0px 1px 0px, rgba(0, 0, 0, 0.14902) 0px 1px 10px 0px; display: block; padding: 0px; background: rgb(255, 255, 255);"></iframe>
        }

        $this->html = $query.'';
    }


    private function instagram_id($url){

        $u = new __url($url);

        $path = $u->path;

        $instagram_id = str_replace('/p/', '', $path);
        $instagram_id = str_replace('/', '', $instagram_id);
        //echo $path.' '.$instagram_id.' ';
        return $instagram_id;
    }



    private function _table(){
        $query = file_get_html($this->html);
        $tbs = $query->find('table');

        if(!isset($tbs[0])) return;

        for ($i=0,$imax=count($tbs); $i < $imax; $i++) { 
            $tb=$tbs[$i];
            $html='';
            $tds=$tb->find('td');
            for ($j=0,$jmax=count($tds); $j < $jmax; $j++) { 
                $td=$tds[$j];
                $html.=$td->innertext;
            }

            $tb->outertext=$html;
        }


        //$this->html=$query->plaintext;
        $this->html=$query.'';


    }



    private function makeIframe($src){

        return '<iframe class="instagram-media instagram-media-rendered" src="'.$src.'" allowtransparency="true" frameborder="0" height="1036" data-instgrm-payload-id="instagram-media-payload-0" scrolling="no" style="border: 0px;margin: 1px;/* max-width: 1000px; */width: calc(100% - 2px);border-radius: 4px;box-shadow: rgba(0, 0, 0, 0.498039) 0px 0px 1px 0px, rgba(0, 0, 0, 0.14902) 0px 1px 10px 0px;display: block;padding: 0px;background: rgb(255, 255, 255);"></iframe>';
    }
}
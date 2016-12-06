<?php 

namespace www_akjunshi_com;


use _scraping\get_html as ab;


class get_html extends ab{


    private $urls;
    private $pattern_content;




    function call($url){

        $html = parent::call($url);

        $this->getConfig($url);

        $this->urls = $this->getUrls($html);

        $html = $this->getHtml();


        return $html;
    }






    private function getUrls($html){

        $urls=[];

        $query = file_get_html($html);

        $as = $query->find('.page_area a');

        if(!$as){
            $urls[] = $this->url;
            return $urls;
        }


        $tmp=[];
        $max=0;

        for ($i=0,$imax=count($as); $i < $imax; $i++) { 
            $a=$as[$i];
            $n = $a->innertext;
            if(is_numeric($n)){
                $tmp[$n] = $a->getAttribute('href');
                $max++;
            }
        }


        for ($i=0; $i < $max; $i++) { 
            $u = $tmp[ ($i+1).''];

            if($u === 'javascript:;') $u = $this->url;
            $urls[] = $u;

        }




        return $urls;

    }





    private function getHtml(){
        $content = $this->mergeContent();
        

        $html = parent::call($this->urls[0]);
        $query = file_get_html($html);
        $doms = $query->find($this->pattern_content);
        $doms[0]->innertext=$content;

        $html = $query.'';

        return $html;
    }





    private function mergeContent(){
        $content='';

        for ($i=0,$imax=count($this->urls); $i < $imax; $i++) { 
            $url=$this->urls[$i];

            $html = parent::call($url);
            $query = file_get_html($html);
            $doms = $query->find($this->pattern_content);
            $content.= $doms[0]->innertext;
        }

        return $content;
    }





    private function getConfig($url){
        $host = parse_url($url, PHP_URL_HOST);
        $us=hostdata();
        $u = $us[$host];

        $this->pattern_content = $u[0];
    }



}
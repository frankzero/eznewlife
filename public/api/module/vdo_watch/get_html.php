<?php 

namespace vdo_watch;


use _scraping\get_html as ab;


class get_html extends ab{




    function call($url){
        $html = parent::call($url);

        $query = file_get_html($html);

        $doms = $query->find('div[data-role="youtube"]');

        for ($i=0,$imax=count($doms); $i < $imax; $i++) { 
            $dom=$doms[$i];
            $id = $dom->getAttribute('id');
            $iframe = '<iframe title="YouTube video player"  itemprop="url" src="https://www.youtube.com/embed/'.$id.'?rel=0" width="650" height="421"></iframe>';
            $dom->outertext = $iframe;
        }

        $html = $query.'';

        return $html;
    }

}
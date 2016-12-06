<?php 

class find_content_weibo_com extends find_content{
    
    protected function getContent(){
        $html = $this->html;

        $html = str_replace('\n', '', $html);
        $html = str_replace('\r', '', $html);
        $html = str_replace('\\', '', $html);

        preg_match_all('/<img src="([^"]+thumb180[^"]+)"/iU', $html, $ms);


        $html = '';
        $urls = $ms[1];
        for ($i=0,$imax=count($urls); $i < $imax; $i++) { 
            $url=$urls[$i];
            $url = str_replace('thumb180', 'mw690', $url);
            $html.='<p><img src="'.$url.'" \></p>';
        }

        $url = 'weibo.com';

        //$html = '<body>'.$html.'</body>';
        
        //$replacer = new replacer($html, 'body', $url);
            
        //$html = $replacer->getHtml();

        return $html;
    }
}
<?php 

abstract class ab_scraping{

    public $url;

    public $host;

    public function __construct($url){
        
        $this->url = $url;

        $this->host = parse_url($url, PHP_URL_HOST);

    }


    abstract public function content();



    protected function handle_response($html){
        $html = preg_replace('/<img([^>]+)(class\s*=\s*"[^"]+")([^>]+)>/', '<img $1 $3 >', $html);

        $html = $this->remove_tag($html, 'div.fb-like');
        $html = $this->remove_tag($html, 'div.fb-page');
        $html = $this->remove_tag($html, 'div.sitemaji_banner');

        $html = str_replace('http://vlog.xuite.net/embed', 'https://vlog.xuite.net/embed', $html);
        $html = str_replace('http://www.youtube.com/', 'https://www.youtube.com/', $html);


        return $html;
    }




    private function remove_tag($html, $selector){
        $query = file_get_html($html);

        $els = $query->find($selector);

        for ($i=0,$imax=count($els); $i < $imax; $i++) { 
            $el=$els[$i];
            $el->outertext='';
        }

        $html = $query.'';


        return $html;
    }


}
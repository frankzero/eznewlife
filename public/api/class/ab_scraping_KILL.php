<?php 

abstract class ab_scraping{

    public function __construct($url, $selector, $title_selector){
        $this->url = $url;
        $this->selector = $selector;
        $this->title_selector = $title_selector;
        
        $html =  $this->file_get_contents($url);

        $this->query = file_get_html( $html );

        // print_r($html);exit;
        // print_r($this->query->find('body')[0]->innertext);

        $host = parse_url($url,PHP_URL_HOST);
        $scheme = parse_url($url,PHP_URL_SCHEME);

        $this->host=$host;
        $this->scheme=$scheme;
        $this->domain = $scheme.'://'.$host;


        //$f = hash('md5', $url).'.txt';
        //file_put_contents(__DIR__.'/log/'.$f, $f);
        $conn=ff\conn();

        $sql="select id from scraping_url WHERE url=:url";
        $row=$conn->getOne($sql, $url);

        if($row===false){
            $sql="insert into scraping_url SET url=:url";
            $conn->insert($sql, $url);
        }


        $this->convertzhtw=false;
        
    }


    private function get_content(){
        $imgs = $this->query->find($this->selector.' img');
        
        $this->replace_image($imgs);

        $doms = $this->query->find($this->selector);

        $html = $doms[0];

        
        $html = $this->handle_html_content($html);
        return $html;
    }


    abstract public function title();


    abstract public function html();


    abstract public function sitename();

    
    private function file_get_contents($url){
        $html =  my_file_get_contents($url);

        // 拿掉 class  不然 file parse 會出錯 
        $html = preg_replace('/<img([^>]+)(class\s*=\s*"[^"]+")([^>]+)>/', '<img $1 $3 >', $html);

        return $html;
    }

}
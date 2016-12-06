<?php 

class catch_content{

    public function __construct($url, $match_content, $match_title, $convert){
        $this->url = $url;
        $this->match_content = $match_content;
        $this->match_title = $match_title;
        $this->convert = $convert;
        $this->host = parse_url($url, PHP_URL_HOST);

    }



    public function response(){


        setSatus('取得網站內容', 0);
        // 取得網站靜態檔案內容 
        $html = $this->get_html($this->url);


        setSatus('簡體處理', 0);
        // 簡體 轉 繁體 
        if($this->convert) $html = $this->convertzhtw($html);



        setSatus('取得標題', 0);
        // 找出 title 
        $title = $this->find_title($html, $this->match_title, $this->url);



        setSatus('取得站名', 0);
        // 找出 sitename 
        $sitename = $this->find_sitename($this->url);



        setSatus('內容處理', 0);
        //找出內容 
        $content = $this->find_content($html, $this->match_content);

        
        setSatus('字串處理', 0);
        // 每個站特別處理字串 
        $content = $this->handle_string($content, $this->url);


        
        setSatus('圖像處理', 0);
        // replace_image   把網站上的圖 抓下來 變自己的圖
        $content = $this->replace_image($content, $this->url); 


        //網址 log 
        $this->url_log($this->url, $title);

        setSatus('完成', 100);
        // 回應
        return $this->package($content, $title, $sitename);

    }




    private function get_html($url){

        $host = $this->host;

        $class_name = 'scraping_'.str_replace('.', '_', $host);

        if(class_exists($class_name)){
            $scraping = new $class_name($url);
        }else{
            $scraping = new scraping($url);
        }

        return $scraping->content();
    }



    private function handle_string($html, $url){
        $host = $this->host;

        $class_name = 'handle_string_'.str_replace('.', '_', $host);

        if(class_exists($class_name)){
            $handle_string = new $class_name($html, $url);
            return $handle_string->response();
        }

        return $html;
    }



    private function convertzhtw($string){
        
        $string = MediaWikiZhConverter::convert($string, "zh-tw");

        return $string;
    }



    private function find_content($html, $match_content){
        $host = $this->host;
        $class_name = 'find_content_'.str_replace('.', '_', $host);

        if(class_exists($class_name)){
            $find_content = new $class_name($html, $match_content);

        }else{
            $find_content = new find_content($html, $match_content);
        }

        return $find_content->content();
    }




    private function replace_image($html, $url){
        $host = $this->host;
        $class_name = 'replacer_'.str_replace('.', '_', $host);

        if(class_exists($class_name)){
            $replacer = new $class_name($html, $url);
        }else{
            $replacer = new replacer($html, $url);
        }

        return $replacer->getHtml();

    }




    private function find_title($html, $match_title, $url){
        $host = $this->host;
        $class_name = 'find_title_'.str_replace('.', '_', $host);

        if(class_exists($class_name)){
            $find_title = new $class_name($html, $match_title);
        }else{
            $find_title = new find_title($html, $match_title);
        }

        return $find_title->getTitle();
    }




    private function find_sitename($url){
        $host = $this->host;
        $class_name = 'find_sitename_'.str_replace('.', '_', $host);

        if(class_exists($class_name)){
            $find_sitename = new $class_name($url);
        }else{
            $find_sitename = new find_sitename($url);
        }

        return $find_sitename->getSiteName();
    }




    private function url_log($url, $title){
        $conn=ff\conn('M');

        $sql="select id from scraping_url WHERE url=:url OR title=:title";
        $row=$conn->getOne($sql, $url, $title);

        if($row===false){
            $sql="insert into scraping_url SET url=:url, title=:title";
            $conn->insert($sql, $url, $title);
        }
    }




    private function package($content, $title, $sitename){

        $split = '/**!@#$%***/';

        $r=[];
        $r[0]=$title;
        $r[1]=$content;
        $r[2]=$sitename;

        return '/*****/'.implode($split, $r).'/*****/';
        // echo '/**!@#$%***/';var_dump($title);return '';
        // return $title.$split.$html;
    }
}
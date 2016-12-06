<?php 
namespace _scraping;

use MediaWikiZhConverter;
use function oconn;


abstract class scraping{


    protected $url;
    protected $pattern_content;
    protected $pattern_title;
    protected $convert;
    protected $host;
    protected $_host;

    protected $get_html;
    protected $find_title;
    protected $find_sitename;
    protected $find_content;
    protected $replace_image;
    protected $_namespace;


    function __construct($url, $ns){
        $this->url = $url;
        $this->host = parse_url($url, PHP_URL_HOST);
        $this->_host = str_replace('.', '_', $this->host);

        $us=hostdata();
        
        $u = $us[$this->host];

        $this->pattern_content = $u[0];
        $this->pattern_title = $u[1];
        $this->convert = $u[2];
        $this->_namespace = $ns;

        $this->get_html = $this->make('get_html');
        $this->find_title = $this->make('find_title');
        $this->find_sitename = $this->make('find_sitename');
        $this->find_content = $this->make('find_content');
        $this->replace_image = $this->make('replace_image');
    }




    private function make($name){
        $class_name = $this->_namespace.'\\'.$name;
        return new $class_name;
    }



    public function response(){


        $this->setSatus('取得網站內容', 0);
        // 取得網站靜態檔案內容 
        $html = $this->get_html->call($this->url);

        $this->setSatus('簡體處理', 0);
        // 簡體 轉 繁體 
        if($this->convert) $html = $this->convertzhtw($html);



        $this->setSatus('取得標題', 0);
        // 找出 title 
        $title = $this->find_title->call($html, $this->pattern_title, $this->url);



        $this->setSatus('取得站名', 0);
        // 找出 sitename 
        $sitename = $this->find_sitename->call($this->url);



        $this->setSatus('內容處理', 0);
        //找出內容 
        $content = $this->find_content->call($html, $this->pattern_content);

        
              
        $this->setSatus('圖像處理', 0);
        // replace_image   把網站上的圖 抓下來 變自己的圖
        $content = $this->replace_image->call($content, $this->url); 


        //網址 log 
        $this->url_log($this->url, $title);
        

        $this->setSatus('完成', 100);
        // 回應
        return $this->package($content, $title, $sitename);

    }








    protected function setSatus($text, $p){
        echo '/***/'.$text.','.$p;
        ob_flush();
        flush();
    }





    protected function url_log($url, $title){
        $conn=oconn('M');

        $sql="select id from scraping_url WHERE url=:url OR title=:title";
        $row=$conn->getOne($sql, $url, $title);

        if($row===false){
            $sql="insert into scraping_url SET url=:url, title=:title";
            $conn->insert($sql, $url, $title);
        }
    }






    protected function package($content, $title, $sitename){

        $split = '/**!@#$%***/';

        $r=[];
        $r[0]=$title;
        $r[1]=$content;
        $r[2]=$sitename;

        return '/*****/'.implode($split, $r).'/*****/';
        // echo '/**!@#$%***/';var_dump($title);return '';
        // return $title.$split.$html;
    }






    protected function convertzhtw($string){
        
        $string = MediaWikiZhConverter::convert($string, "zh-tw");

        return $string;
    }
    
}
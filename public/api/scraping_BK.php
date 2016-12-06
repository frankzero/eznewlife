<?php 
require __DIR__.'/autoload.php';

// echo MediaWikiZhConverter::convert("大家有没有发现，周润发已经成了自拍界的集邮天王，走到哪儿就跟人自拍到哪儿", "zh-tw");//轉台灣繁體
// echo MediaWikiZhConverter::convert("記憶體", "zh-cn");//轉大陸簡體
// echo MediaWikiZhConverter::convert("罗纳尔多", "zh-hk");//轉香港繁體
// exit;



//print_r($_POST);
api_handle();

function api_handle(){

    $url = http::post('url');

    if(!$url) return;


    $host = parse_url($url,PHP_URL_HOST);


    $urls = [];

    // content  title  簡體 
    // $urls['life.tw'] = ['#mainContent', '.aricle-detail-top h1', false];




    if($host === 'life.tw'){

        $scraping = new scraping($url, '#mainContent', '.aricle-detail-top h1');
        
        echo $scraping->response();
        return;
    }






    if($host === 'www.buzzhand.com'){
        $scraping = new scraping($url, '#articleContent', '#pageTitle');
        echo $scraping->response();
        return;
    }






    if($host === 'moviesun.org'){
        //$scraping = new scraping($url, '.mh-content', 'h1.entry-title');
        $scraping = new scraping($url, '.entry', 'h1.entry-title');
        echo $scraping->response();
        return;
    }





    if($host === 'www.bomb01.com'){
        $scraping = new scraping($url, '#content', 'h1.title');
        echo $scraping->response();
        return;
    }





    if($host === 'toments.com'){
        $scraping = new scraping($url, '.Content_post', 'h3.article_title');
        echo $scraping->response();
        return;
    }





    if($host === 'www.teepr.com'){
        $scraping = new scraping($url, '.post-single-content', 'h1.title');
        echo $scraping->response();
        return;
    }




    // block
    if($host === 'www.muse01.com'){
        $scraping = new scraping($url, '.thecontent', 'h1.title span');
        echo $scraping->response();
        return;
    }



    // block
    if($host === 'tw.anyelse.com'){
        $scraping = new scraping($url, '.artbody', '.artshow h1');
        echo $scraping->response();
        return;
    }





    if($host === 'mega-shares.com'){
        $scraping = new scraping($url, 'div.Content-post', '.title h3');
        echo $scraping->response();
        return;
    }





    if($host === 'www.ntdtv.com'){
        // .v2015_text h1 
        $scraping = new scraping($url, 'div.wysiwyg', '#v2015_text h1');
        echo $scraping->response();
        return;
    }





    if($host === 'home.gamer.com.tw'){
        $scraping = new scraping($url, 'div.MSG-list8C', 'h1.TS1');
        echo $scraping->response();
        return;   
    }




    if(  $host==='gnn.gamer.com.tw' ){
        // #BH-master h1 
        // .GN-lbox3B div
        $scraping = new scraping($url, '.GN-lbox3B div', '#BH-master h1');
        echo $scraping->response();
        return;
    }
    




    if(  $host==='www.fullyu.com' ){
        // #BH-master h1 
        // .GN-lbox3B div
        $scraping = new scraping($url, '#post-content', '#post-content h1');
        echo $scraping->response();
        return;
    }




    
    if(  $host==='toutiao.com' ){
        
        $scraping = new scraping($url, '.article-content', '.title h1');
        $scraping->convertzhtw();
        echo $scraping->response();
        return;
    }






    echo '<h1>網址不支援</h1>';
}






class scraping{
    
    public function __construct($url, $selector, $title_selector){
        $this->url = $url;
        $this->selector = $selector;
        $this->title_selector = $title_selector;
        $this->query = file_get_html( my_file_get_contents($url) );

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



    public function convertzhtw(){
        $this->convertzhtw=true;
    }


    public function response(){
        $html = $this->html();
        $title = $this->title();

        //echo '/**!@#$%***/';var_dump($title);return '';
        return $title.'/**!@#$%***/'.$html;
    }


    public function html(){

        $string = $this->get_content($this->selector);

        if($this->convertzhtw===true){
            $string = $this->convert($string);
        }

        return $string;
    }



    public function convert($string){
        
        $string = MediaWikiZhConverter::convert($string, "zh-tw");

        return $string;
    }


    public function title(){
        $doms = $this->query->find($this->title_selector);
        $title = $doms[0]->innertext;
        $title = preg_replace("/<img[^>]+\>/i", "", $title); 
        $title = html_entity_decode($title);

        if($this->convertzhtw===true){
            $title = $this->convert($title);
        }

        return $title;
    }


    private function get_content($selector){

        $imgs = $this->query->find($selector.' img');
        
        $this->replace_image($imgs);

        // echo count($imgs);
        // echo $imgs[0]->getAttribute('src');
        // $imgs[0]->setAttribute('src', 'http://images.900.tw/upload_file/30/content/fb358ed4-56a8-1834-13c8-265012922b00.png');
        $doms = $this->query->find($selector);

        $html = $doms[0];

        //$html=strip_tags($html,'<div><p><a>')
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);

        return $html;
    }


    private function getimageFileType($file){
        $file = str_replace('http://', '', $file);
        $file = str_replace('https://', '', $file);

        $file  = pathinfo($file, PATHINFO_EXTENSION);

        if(strpos($file, '?') !== false){
            $file = explode('?', $file);
            $file = $file[0];
        }


        if($file==='') $file='jpg';

        return $file;
    }


    private function replace_image($imgs){
    
        for ($i=0,$imax=count($imgs); $i < $imax; $i++) { 
            $img=$imgs[$i];

            if($this->host==='toments.com'){
                $url = $img->getAttribute('adonis-src');
            }else{
                $url = $img->getAttribute('src');
            }


            $imageFileType  = $this->getimageFileType($url);

            if(strpos($url, 'http') === false){
                $url = $this->domain.$url;
            }


            $filename=time().'-'.unique_id(5).'.'.$imageFileType;
            $file=__DIR__.'/../uploads/'.$filename;
            $image_url = '/uploads/'.$filename;

            $image_string = file_get_contents($url);

            file_put_contents($file, $image_string);

            $img->setAttribute('src',$image_url);
            $img->setAttribute('data-mce-src',$image_url);

        }
    }

}






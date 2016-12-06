<?php 
require __DIR__.'/autoload.php';

// echo MediaWikiZhConverter::convert("大家有没有发现，周润发已经成了自拍界的集邮天王，走到哪儿就跟人自拍到哪儿", "zh-tw");//轉台灣繁體
// echo MediaWikiZhConverter::convert("記憶體", "zh-cn");//轉大陸簡體
// echo MediaWikiZhConverter::convert("罗纳尔多", "zh-hk");//轉香港繁體
// exit;


//print_r($_POST);
api_handle();



function hostdata(){
    $urls = [];

    // 0 content  
    // 1 title  
    // 2 轉簡體 

    $urls['life.tw']              = ['#mainContent',         '.aricle-detail-top h1',      false];
    $urls['www.life.com.tw']      = ['#mainContent',         '.aricle-detail-top h1',      false];
    $urls['www.buzzhand.com']     = ['#articleContent',      '#pageTitle',                 false];
    $urls['moviesun.org']         = ['.entry',               'h1.entry-title',             false];
    $urls['www.bomb01.com']       = ['#content',             'h1.title',                   false];
    $urls['toments.com']          = ['.Content_post',        'h3.article_title',           false];
    $urls['www.teepr.com']        = ['.post-single-content', 'h1.title',                   false];
    $urls['www.muse01.com']       = ['.thecontent',          'h1.title span',              false]; // block
    $urls['tw.anyelse.com']       = ['.artbody',             '.artshow h1',                false]; // block 
    $urls['www.xianso.com']       = ['.artbody',             '.artshow h1',                false]; // block 
    $urls['mega-shares.com']      = ['div.Content-post',     '.title h3',                  false];
    $urls['www.ntdtv.com']        = ['div.wysiwyg',          '#v2015_text h1',             true ];
    $urls['home.gamer.com.tw']    = ['div.MSG-list8C',       'h1.TS1',                     false];
    $urls['gnn.gamer.com.tw']     = ['.GN-lbox3B div',       '#BH-master h1',              false];
    $urls['www.fullyu.com']       = ['#post-content',        '#post-content h1',           false];
    $urls['toutiao.com']          = ['.article-content',     '.title h1',                  true ];
    $urls['ww.daliulian.net']     = ['#article-content',     '.module h3 a',               false];
    $urls['www.gjoyz.com']        = ['.div_object_desc',     'h1.objectTitle',             false];
    $urls['buzzlife.com.tw']      = ['#content1',            '.page-header h1',            false];
    $urls['www.fun01.cc']         = ['div.postContent',      '.content  h1 a',             false];
    $urls['ck101.com']            = ['#lightboxwrap',       '#thread_subject',             false];

    return $urls;
}

 
function api_handle(){

    $allowurl=http::get('allowurl');


    if($allowurl){
        $urls=hostdata();
        require __DIR__.'/allowurl.html';
        return;
    }


    $url = http::post('url');

    if(!$url) return;


    $host = parse_url($url,PHP_URL_HOST);


    $urls=hostdata();
    

    if( isset($urls[$host]) ){
        $u=$urls[$host];
        $match_content = $u[0];
        $match_title = $u[1];
        $convert = $u[2];

        $scraping = new scraping($url, $match_content, $match_title);

        if($convert) $scraping->convertzhtw();

        echo $scraping->response();
        return;


    }
    

    echo '<h1>網址不支援</h1>';
}




function filename($imageFileType){
    static $i=0;

    $i++;

    return 'test-'.$i.'.'.$imageFileType;
}

class scraping{
    
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


    public function convertzhtw(){
        $this->convertzhtw=true;
    }


    public function response(){
        $split = '/**!@#$%***/';

        $html = $this->html();
        $title = $this->title();
        $sitename = $this->getsitename();


        $r=[];
        $r[0]=$title;
        $r[1]=$html;
        $r[2]=$sitename;

        return implode($split, $r);
        // echo '/**!@#$%***/';var_dump($title);return '';
        // return $title.$split.$html;
    }


    public function getsitename(){
        $html =  $this->file_get_contents($this->domain);

        $query = file_get_html( $html );

        $doms = $query->find('title');

        $title = $doms[0]->innertext;

        $title=$this->remove_script($title);
        $title = preg_replace("/<img[^>]+\>/i", "", $title); 
        $title = html_entity_decode($title);

        return $title;
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
        $title=$this->remove_script($title);
        $title = preg_replace("/<img[^>]+\>/i", "", $title); 
        $title = html_entity_decode($title);

        if($this->convertzhtw===true){
            $title = $this->convert($title);
        }

        return $title;
    }


    private function file_get_contents($url){
        $html =  my_file_get_contents($url);

        // 拿掉 class  不然 file parse 會出錯 
        $html = preg_replace('/<img([^>]+)(class\s*=\s*"[^"]+")([^>]+)>/', '<img $1 $3 >', $html);

        return $html;
    }


    private function get_content($selector){

        $imgs = $this->query->find($selector.' img');
        
        $this->replace_image($imgs);

        // echo count($imgs);
        // echo $imgs[0]->getAttribute('src');
        // $imgs[0]->setAttribute('src', 'http://images.900.tw/upload_file/30/content/fb358ed4-56a8-1834-13c8-265012922b00.png');
        $doms = $this->query->find($selector);

        $html = $doms[0];

        
        $html = $this->handle_html_content($html);
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
 

    private function handle_image_src($img){
        if($this->host==='toments.com'){
            $url = $img->getAttribute('data-src');
            // echo 'bbb '.$url."\n";
            if(!$url) $url = $img->getAttribute('adonis-src');
            // echo 'ccc '.$url."\n";

            $url = $this->make_image_url($url);

            return $url;
        }
        
        if($this->host==='www.gjoyz.com'){
            $url = $img->getAttribute('data-original');

            if(!$url) $url = $img->getAttribute('src');
            
            $url = $this->make_image_url($url);

            return $url;

        }


        if($this->host==='buzzlife.com.tw'){
            $url = $img->getAttribute('data-src');

            $url = $this->make_image_url($url);

            return $url;

        }

        

        if($this->host === 'ck101.com'){
            $url = $img->getAttribute('file');

            if(!$url){
                $url = $img->getAttribute('src');
            }

            $url = $this->make_image_url($url);

            return $url;
        }

        $url = $img->getAttribute('src');
        if(!$url) $url = $img->getAttribute('data-src');


        $url = $this->make_image_url($url);
        return $url;
        
    }

    //data-original

    private function replace_image($imgs){
    
        for ($i=0,$imax=count($imgs); $i < $imax; $i++) { 
            $img=$imgs[$i];

            $url = $this->handle_image_src($img);

            $imageFileType  = $this->getimageFileType($url);


            // echo 'aaa '.$url."\n";

            $filename=time().'-'.unique_id(5).'.'.$imageFileType;
            //$filename=filename($imageFileType);
            $file=__DIR__.'/../uploads/'.$filename;
            $image_url = '/uploads/'.$filename;

            $image_string = @file_get_contents($url);

            if($image_string === false){
                $img->setAttribute('src','');
                $img->setAttribute('data-mce-src','');
                continue;
            }

            
            file_put_contents($file, $image_string);

            $img->setAttribute('src',$image_url);
            $img->setAttribute('data-mce-src',$image_url);

        }
    }


    private function make_image_url($path){
        if(strpos($path, 'http') !== false) return $path;

        if($this->host==='toments.com'){
            return 'http://file.toments.com'.$path;
        }

        if(strpos($path, '/') !== 0) $path = '/'.$path;

        return $this->domain.$path;
    }


    private function handle_html_content($html){

        //$html=strip_tags($html,'<div><p><a>')
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        $html = preg_replace('#<ins(.*?)>(.*?)</ins>#is', '', $html);

        // teepr 拿掉 srcset
        $html = preg_replace("#(<img[^>]+)srcset=\"[^\"]+\"([^>]+>)#", '$1 $2', $html);

        //拿掉 html 註解 
        $html = preg_replace("#<!--(?!<!)[^\[>][\s\S]*?-->#", '$1 $2', $html);
        
        return $html;
    }


    private function remove_script($html){
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        $html = preg_replace('#<ins(.*?)>(.*?)</ins>#is', '', $html);
        return $html;
    }

}






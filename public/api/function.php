<?php 


function hostdata(){
   require __API__.'config.php';

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
    $u = getUrlConfig($url);
    
    if(!$u){
        echo '<h1>網址不支援</h1>';
        return;
    }


    $host = parse_url($url,PHP_URL_HOST);
    $_host = str_replace('.', '_', $host);

    if( file_exists(__API__.'module/'.$_host) ){
        $class = $_host.'\\scraping';

        $cc = new $class($url);

        echo $cc->response();

        return;
    }

    $match_content = $u[0];
    $match_title = $u[1];
    $convert = $u[2];

    $cc = new catch_content($url, $match_content, $match_title, $convert);

    echo $cc->response();
    //catch_content($url, $match_content, $match_title, $convert);
    return;
    

    
}




function getUrlConfig($url){
    //$url = http::post('url');

    if(!$url) return false;

    $url = handle_url($url);


    $host = parse_url($url,PHP_URL_HOST);


    $urls=hostdata();

    
    
    if( !isset($urls[$host]) ){
        return false;
    }


    if(isset( $urls[$host] )) return $urls[$host];

   
    return false;
}



function handle_url($url){
    if(strpos($url, 'eyny.com') !== false){

        $u = new __url($url);
        $u->host = 'www256.eyny.com';

        return $u->url();
    }


    return $url;
}



function setSatus($text, $p){
    echo '/***/'.$text.','.$p;
    ob_flush();
    flush();
}






class http{

    public static function get($key=null){

        return $key===null ? $_GET : (isset($_GET[$key]) ? $_GET[$key] : false);
    }

    public static function post($key=null){
        return $key===null ? $_POST : (isset($_POST[$key]) ? $_POST[$key] : false);
    }

    public static function put($key=null){

        static $_PUT=null;

        if($_PUT === null) parse_str(file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH'] ), $_PUT);

        return $key===null ? $_PUT : (isset($_PUT[$key]) ? $_PUT[$key] : false);
    }

    public static function delete($key=null){
        static $_DELETE=null;

        if($_DELETE === null) parse_str(file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH'] ), $_DELETE);

        return $_DELETE===null ? $_DELETE : (isset($_DELETE[$key]) ? $_DELETE[$key] : false);
    }

    public static function header($key=null){
        static $_HEADER=null;

        if($_HEADER === null) $_HEADER = getallheaders();

        return $_HEADER===null ? $_HEADER : (isset($_HEADER[$key]) ? $_HEADER[$key] : false);
    }


    public static function cookie($key=null){
        return $key===null ? $_COOKIE : (isset($_COOKIE[$key]) ? $_COOKIE[$key] : false);
    }

    public static function method(){
        return $_SERVER['REQUEST_METHOD'];
    }


    public static function path(){
        $path = $_SERVER['REQUEST_URI'];
        $path = explode('?', $path);
        $path=$path[0];
        return $path;
    }


}


function my_file_get_contents($url){
    
    
    $host = parse_url($url,PHP_URL_HOST);
    
    if($host === 'www.muse01.com'){

        $contents = file_get_contents($url);
        $spider='';

        //echo $contents;
        $tmp = explode('spider=', $contents);
        $tmp = $tmp[1];
        $tmp = explode('";', $tmp);
        $spider=$tmp[0];
        //echo 'spider='.$spider;
        $options = array(
          'http'=>array(
            'method'=>"GET",
            'header'=>"Accept-Language:zh-TW,zh;q=0.8,en-US;q=0.6,en;q=0.4,zh-CN;q=0.2,es;q=0.2,ja;q=0.2,ru;q=0.2,fr;q=0.2,it;q=0.2,gl;q=0.2\r\n" .
                      //"Cookie: __cfduid=dec2b3c0791aebc8c4f2325b449d4b66a1453989557; PHPSESSID=6emmk7l3p1jg3f8d1f90vhkip6; spider=b5ab2098454eed4b756cda9bcce06711; _gat=1; _ga=GA1.2.149316593\r\n" .  // check function.stream-context-create on php.net
                      "User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36\r\n" // i.e. An iPad 
                      ."Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\r\n"
                      //."Accept-Encoding:gzip, deflate, sdch\r\n"
                      ."Cache-Control:no-cache\r\n"
                      ."Connection:keep-alive\r\n"
                      ."Cookie:__cfduid=dec2b3c0791aebc8c4f2325b449d4b66a1453989557; PHPSESSID=6emmk7l3p1jg3f8d1f90vhkip6; spider=$spider; _gat=1; _ga=GA1.2.1493165931.1453989559\r\n"
                      ."Host:www.muse01.com\r\n"
                      ."Pragma:no-cache\r\n"
                      ."Referer:".$url."\r\n"
                      ."Upgrade-Insecure-Requests:1\r\n"

          )
        );

        $context = stream_context_create($options);
        $file = file_get_contents($url, false, $context);

        return $file;
    }


    if($host === 'tw.anyelse.com' || $host === 'www.xianso.com'){
        $options = array(
          'http'=>array(
            'method'=>"GET",
            'header'=>"Accept-Language:zh-TW,zh;q=0.8,en-US;q=0.6,en;q=0.4,zh-CN;q=0.2,es;q=0.2,ja;q=0.2,ru;q=0.2,fr;q=0.2,it;q=0.2,gl;q=0.2\r\n" .
                      "User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36\r\n" // i.e. An iPad 
                      ."Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\r\n"
                      // ."Cache-Control:no-cache\r\n"
                      // ."Connection:keep-alive\r\n"
                      // ."Cookie:__cfduid=dec2b3c0791aebc8c4f2325b449d4b66a1453989557; PHPSESSID=6emmk7l3p1jg3f8d1f90vhkip6; spider=$spider; _gat=1; _ga=GA1.2.1493165931.1453989559\r\n"
                      // ."Host:www.muse01.com\r\n"
                      // ."Pragma:no-cache\r\n"
                      // ."Referer:".$url."\r\n"
                      // ."Upgrade-Insecure-Requests:1\r\n"

          )
        );

        $context = stream_context_create($options);
        $file = file_get_contents($url, false, $context);

        return $file;
    }


    if($host === 'fun01.cc'){

        $options = array(
          'http'=>array(
            'method'=>"GET",
            'header'=>"Accept-Language:zh-TW,zh;q=0.8,en-US;q=0.6,en;q=0.4,zh-CN;q=0.2,es;q=0.2,ja;q=0.2,ru;q=0.2,fr;q=0.2,it;q=0.2,gl;q=0.2\r\n" .
                      "User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36\r\n" // i.e. An iPad 
                      ."Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\r\n"
                      //."Cookie:__cfduid=dec2b3c0791aebc8c4f2325b449d4b66a1453989557; PHPSESSID=6emmk7l3p1jg3f8d1f90vhkip6; spider=$spider; _gat=1; _ga=GA1.2.1493165931.1453989559\r\n"
                      ."Host:fun01.cc\r\n"
                      ."Pragma:no-cache\r\n"
                      ."Referer:".$url."\r\n"
                      ."Upgrade-Insecure-Requests:1\r\n"
                      ."CF-RAY:2b34ab75ff584650-TPE\r\n"

          )
        );


        $options = array(
          'http'=>array(
            'method'=>"GET",
            'header'=>"Accept-Language:zh-TW,zh;q=0.8,en-US;q=0.6,en;q=0.4,zh-CN;q=0.2,es;q=0.2,ja;q=0.2,ru;q=0.2,fr;q=0.2,it;q=0.2,gl;q=0.2\r\n" .
                      "User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36\r\n" // i.e. An iPad 
                      ."Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\r\n"
                      //."Cookie:__cfduid=dec2b3c0791aebc8c4f2325b449d4b66a1453989557; PHPSESSID=6emmk7l3p1jg3f8d1f90vhkip6; spider=$spider; _gat=1; _ga=GA1.2.1493165931.1453989559\r\n"
                      ."Host:fun01.cc\r\n"
                      ."Pragma:no-cache\r\n"
                      ."Referer:".$url."\r\n"
                      ."Upgrade-Insecure-Requests:1\r\n"
                      ."CF-RAY:2b34ab75ff584650-TPE\r\n"

          )
        );


        if(strpos($url, '?') ===false){
            $myurl = $url.'?agree=1';
        }else if(strpos($url, 'agree') ===false){
            $myurl = $url.'&agree=1';
        }else{
            $myurl = $url;
        }


        $context = stream_context_create($options);
        $file = file_get_contents($myurl, false, $context);

        return $file;

    }



    $r = file_get_contents($url);

    //echo $r;

    return $r;
}



function conn(){
    static $conn=null;

    if($conn !== null) return $conn;

    $db_user='admin';
    $db_host='localhost';
    $db_password='10281028';
    $db_name='eznewlife_miu';

    $dsn = "mysql:host=$db_host;dbname=$db_name";
    $conn = new __mypdo($dsn, $db_user, $db_password);
    return $conn;
}




function is_mobile(){

    static $is_mobile = null;

    if($is_mobile !== null) return $is_mobile;

    $d = new device;

    if($d->device_code === 1){
        $is_mobile=1;
        return $is_mobile;
    }

    $is_mobile=0;
    return $is_mobile;
}
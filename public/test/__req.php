<?php 

class __req{

    public $param;
    public $cookie;
    public $header;
    public $http_response_header;


    public function __construct($url, $method='GET', $param=null){
        
        $this->url = $url;

        $this->method = $method;

        $this->host = parse_url($url,PHP_URL_HOST);
        $this->path = parse_url($url,PHP_URL_PATH);

        $this->cookie=[];

        $this->header=[];

        $this->param=[];

        $this->header['Accept-Language']='zh-TW,zh;q=0.8,en-US;q=0.6,en;q=0.4,zh-CN;q=0.2,es;q=0.2,ja;q=0.2,ru;q=0.2,fr;q=0.2,it;q=0.2,gl;q=0.2';
        $this->header['User-Agent']='Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36';
        $this->header['Accept']='text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
        $this->header['Cache-Control']='no-cache';
        $this->header['Upgrade-Insecure-Requests']='1';
        $this->header['Pragma']='no-cache';
        $this->header['Connection']='keep-alive';
        $this->header['Host']=$this->host;


        if($method==='POST'){
            $this->header['Content-type'] = 'application/x-www-form-urlencoded';
        }


        if($param!==null){
            $this->param=$param;
        }
    }

    


    public function setCookie($property, $value){

        $this->cookie[$property]=$value;
    }




    public function setHeader($property, $value){
        if($property === 'Cookie'){
            $this->loadCookie($value);
            return;
        }

        $this->header[$property]=$value;
    }



    public function setParam($property, $value){
        $this->param[$property]=$value;
    }



    public function response(){

        if($this->method === 'GET'){
            return $this->doGET();
        }


        if($this->method === 'POST'){
            return $this->doPOST(); 
        }


        return '';
        
    }




    public function show(){
        echo "<pre>";
        echo "====================================================\n";
        echo $this->url.' '.$this->options['http']['method']."\n";
        echo "<h3> header </h3>";
        echo $this->options['http']['header'];

        echo "<h3>param</h3>\n";
        if( isset($this->options['http']['content']) ) $this->dd($this->options['http']['content']);


        echo "<h3>response header</h3>";
        $this->dd($this->http_response_header);


        // echo "lastest cookie";
        // $r = file_get_contents($this->cookiefile);
        // $this->dd(json_decode($r));




        echo "</pre>";
    }




    protected function client_ip(){
        static $ip = null;
        
        if($ip === null){
            if(empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $ip = (!empty($_SERVER['REMOTE_ADDR']))?$_SERVER['REMOTE_ADDR']:'';
            }else{
                $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $ip = $ip[0];
            }
        }
        
        return $ip;
    }



    protected function doGET(){
        $headerstring = $this->makeHeaderString();
        
        $options = array(
          'http'=>array(
            'method'=>$this->method,
            'header'=>$headerstring
          )
        );

        $content = $this->get_content($options);

        return $content;
    }




    protected function doPOST(){

        $postdata = http_build_query($this->param);

        $this->header['Content-Length'] = strlen($postdata);


        if(!isset($this->header['Content-type'])){
            $this->header['Content-type'] = 'application/x-www-form-urlencoded';
        }

        $headerstring = $this->makeHeaderString();

        $options = array(
          'http'=>array(
            'method'=>$this->method,
            'header'=>$headerstring,
            'content' => $postdata
          )
        );

        $content = $this->get_content($options);

        return $content;

    }



    protected function get_content($options){

        $context = stream_context_create($options);

        $content = file_get_contents($this->url, false, $context);

        $this->http_response_header = $http_response_header;

        $this->response = $content;

        $this->options = $options;

        return $content;
    }


    protected function makeCookieString(){
        $cookie_string = '';

        foreach($this->cookie as $property => $value){
            $cookie_string.=$property.'='.$value.'; ';
        }

        return $cookie_string;
    }



    protected function makeHeaderString(){
        
        $cookiestring = $this->makeCookieString();

        if($cookiestring) $this->header['Cookie'] = $cookiestring;


        $str='';
        foreach($this->header as $property => $value){
            $str.=$property.':'.$value."\r\n";
        }
        return $str;

    }



    protected function loadCookie($cookieString){

        $cookie = $this->cookie_to_array($cookieString);

        foreach($cookie as $prop => $value){
            $this->cookie[$prop]=$value;
        }
    }



    protected function cookie_to_array($cookieString){
        $cookie=[];

        $rs = explode(';', $cookieString);
        for ($i=0,$imax=count($rs); $i < $imax; $i++) { 
            $r=$rs[$i];
            $r = trim($r);

            if($r==='') continue;

            $q = explode('=', $r);
            $property = $q[0];
            $value = str_replace($property.'=', '', $r);

            $cookie[$property]=$value;
        }

        return $cookie;
    }


    protected function dd($data){
        $h = '';

        if(is_string($data)){
            $h.="<pre>\n";
            $h.=$data;
            $h.="</pre>\n";

            echo $h;
            return;
        }

        $h.="<pre>\n";


        foreach($data as $prop => $value){

            $h.=$prop.' '.$value."\n";
        }

        $h.="</pre>\n";

        echo $h;

    }



}
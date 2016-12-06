<?php 

class __request extends __req{
    
    public $cookiefile;

    public $debug=false;


    private $fullCookies=[];


    public function __construct($url, $method='GET', $param=null){

        parent::__construct($url, $method, $param);

        // $this->cookiefile = tempnam(sys_get_temp_dir(), md5($this->client_ip().$this->host));
        $this->cookiefile = sys_get_temp_dir().'/'. md5($this->client_ip().$this->host);


    }



    public function response(){

        $this->readFileCookie();

        $r = parent::response();

        if($this->debug) $this->show();

        return $r;
    }



    public function clearCookie(){
        if(file_exists($this->cookiefile)){
            unlink($this->cookiefile);
        }
    }


    public function show(){
        echo "<pre>";
        echo "<h1>====================================================</h1>";
        echo '<h3 style="margin:0">'.$this->options['http']['method'].' '.$this->url.'</h3>'."\n";
        
        echo '<h3 style="margin:0">HEADER</h3>';
        echo $this->options['http']['header'];

        echo '<h3 style="margin:0">COOKIE</h3>';
        $cookies = $this->cookie_to_array($this->header['Cookie']);
        foreach($cookies as $prop => $value){
            echo $prop.' = '.$value."\n";
        }

        echo '<h3 style="margin:0">PARAM</h3>';
        if( isset($this->options['http']['content']) ) {
            parse_str($this->options['http']['content'], $output);
            $this->dd($output);
        }


        echo '<h3 style="margin:0">RESPONSE HEADER</h3>';
        foreach($this->http_response_header as $prop => $value){
            if( strpos($value, 'Set-Cookie') !== false){
                echo $value."\n";
                //echo $prop.' '.$v[0].'  =  '.$v[1];
            }
        }
        //$this->dd($this->http_response_header);


        echo '<h3 style="margin:0">LASTEST COOKIE</h3>';
        $r = file_get_contents($this->cookiefile);
        echo $r;
        //$this->dd(json_decode($r));




        echo "</pre>";
    }




    public function setCookie($property, $value, $expires=null, $path='/'){

        if($expires===null){
            $expires = date('Y-m-d H:i:s',time()+3600*24*30);
        }

        $t = strtotime($expires);


        if( $path !== '/' && $this->path !== $path){
            if($t >= time()) $this->fullCookies[$property]= $property.'='.$value.'; expires='.$expires.' ;path='.$path.' ;domain=.'.$this->host;
            //echo 'no cookie '.$path.' '.$expires."\n";
            return;
        }


        if($t >= time()){
            parent::setCookie($property, $value);
            $this->fullCookies[$property]= $property.'='.$value.'; expires='.$expires.' ;path='.$path.' ;domain=.'.$this->host;
            return;
        }
        

    }

    /*
    // 吸收 header資訊  , setcookie
    public function loadResponse($http_response_header){
        for ($i=0,$imax=count($http_response_header); $i < $imax; $i++) { 
            $d=$http_response_header[$i];
            if(strpos($d, 'Set-Cookie') !== false){
                echo $d."\n";
                $tmp = explode(':', $d);
                $prop = $tmp[0];
                $value = str_replace($prop.':', '', $d);
                $req2->setHeader($prop, $value);
                //$req2->loadCookie($d);
            }
        }
    }
    */



    protected function get_content($options){


        $content = parent::get_content($options);
        // echo "====================================================\n";
        // echo $this->url.' '.$options['http']['method']."\n";
        // $this->dd($options['http']['header']);
        // if( isset($options['http']['content']) ) $this->dd($options['http']['content']);
        // $this->dd($this->http_response_header);

        $this->saveCookie();

        return $content;
    }


    private function saveCookie_BK(){

        $cookie=[];

        foreach( $this->cookie as $prop => $value){
            $cookie[$prop]=$value;
        }


        foreach ($this->http_response_header as $hdr) {
            
            if (preg_match('/^Set-Cookie:\s*([^;]+)/', $hdr, $matches)) {

                $tmp = explode('=',$matches[1]);
                $prop = $tmp[0];
                $value = str_replace($prop.'=', '', $matches[1]);

                if($prop==='djAX_e8d7_6dcbeea827aae0d71dc2a6d18e3d4b4c') continue;
                
                $cookie[$prop]=$value;
                //$cookie[$prop]=urldecode($value);
            }
        }

        file_put_contents($this->cookiefile, json_encode($cookie));

    }


    private function saveCookie(){

        $cookiestring='';

        foreach ($this->http_response_header as $hdr) {
            
            if(strpos($hdr, 'Set-Cookie:') !== false){
                $str=trim(str_replace('Set-Cookie:', '', $hdr));
                $c = $this->parseSetCookie($str);
                $this->setCookie($c->prop, $c->value, $c->expires, $c->path);
            }
        }
        

        foreach( $this->fullCookies as $prop => $value){
            $cookiestring.=$value."\n";
        }


        

        file_put_contents($this->cookiefile, $cookiestring);

    }


    private function parseSetCookie($str){
            
        $str=trim($str);

        //echo 'qqq '.$str."\n";
        if(!$str) return false;

        $c = new stdClass;

        $c->prop='';
        $c->value='';
        $c->expires=null;
        $c->path='/';


        //djAX_e8d7_lastvisit=1460961513; expires=Wed, 18-May-2016 07:38:33 GMT; path=/; domain=.eyny.com
        $v = explode(';', $str);
        $tmp = explode('=', $v[0]);
        $c->prop=$tmp[0];
        $c->value=$tmp[1];

        for ($i=0,$imax=count($v); $i < $imax; $i++) { 
            $d=$v[$i];
            
            if(strpos($d, 'expires=') !==false){
                $c->expires=str_replace('expires=', '', trim($d));
                continue;
            }


            if(strpos($d, 'path=') !==false){
                $c->path=str_replace('path=', '', trim($d) );
                continue;
            }

        }

        return $c;
    }



    private function readFileCookie(){
        
        if(!file_exists($this->cookiefile)) return;

        $r = file_get_contents($this->cookiefile);
        if(!$r) return;
        
        $rs = explode("\n", $r);

        for ($i=0,$imax=count($rs); $i < $imax; $i++) { 
            $r=$rs[$i];
            $r = trim($r);

            if(!$r) continue;
            
            $c = $this->parseSetCookie($r);
            $this->setCookie($c->prop, $c->value, $c->expires, $c->path);
        }
    }
}
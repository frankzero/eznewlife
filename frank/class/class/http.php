<?php 

class http{

    public static function get($key=null){

        return $key===null ? $_GET : (isset($_GET[$key]) ? $_GET[$key] : false);
    }

    public static function post($key=null){
        return $key===null ? $_POST : (isset($_POST[$key]) ? $_POST[$key] : false);
    }

    public static function put($key=null){

        static $_PUT=null;

        if($_PUT === null) parse_str(file_get_contents('php://input', false , null, -1 ), $_PUT);

        return $key===null ? $_PUT : (isset($_PUT[$key]) ? $_PUT[$key] : false);
    }

    public static function delete($key=null){
        static $_DELETE=null;

        if($_DELETE === null) parse_str(file_get_contents('php://input', false , null, -1  ), $_DELETE);

        return $_DELETE===null ? $_DELETE : (isset($_DELETE[$key]) ? $_DELETE[$key] : false);
    }

    public static function header($key=null){
        static $_HEADER=null;

        if($_HEADER === null) $_HEADER = getallheaders();

        return $_HEADER===null ? $_HEADER : (isset($_HEADER[$key]) ? $_HEADER[$key] : false);
    }


    // 半年 
    public static function cookie($key, $value=null, $time=15552000, $path='/' ){
        // return $key===null ? $_COOKIE : (isset($_COOKIE[$key]) ? $_COOKIE[$key] : false);
        if( $value === null ){
            if(!isset($_COOKIE[$key]) ){
                return '';
            }
            return $_COOKIE[$key];
        }else{
            $time = time()+$time;
            setcookie($key, $value, $time, $path);
        }

    }



    public static function deleteCookie($key){
        unset($_COOKIE[$key]);

        setcookie($key,null,-1,'/');
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


    public static function current_url(){
        static $current_url = null;
        
        if(null === $current_url){
            $current_url = 'http';
            if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$current_url .= "s";}
            $current_url .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $current_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]; // REQUEST_URI ,PHP_SELF
            } else {
                $current_url .= $_SERVER["SERVER_NAME"];
            }
        }
        
        return $current_url;
    }


    public static function current_page_url(){
        static $current_page_url = null;

        if($current_page_url !== null)  return $current_page_url;

        $current_page_url = 'http';
        if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$current_page_url .= "s";}
        $current_page_url .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $current_page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; // REQUEST_URI ,PHP_SELF
        } else {
            $current_page_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        $current_page_url = explode('?',$current_page_url);
        $current_page_url = $current_page_url[0];
        $current_page_url = urldecode($current_page_url);

        return $current_page_url;
    }



    public static function build_query($query_array, $enc_type='&'){
        if ( function_exists('http_build_query') ){
            
            return http_build_query($query_array, '', $enc_type);
            
        }else{
            
            $query = '';
            $new_query = array();
            foreach( $query_array as $key => $key_value ){
                $new_query[] = urlencode( $key ) . '=' . urlencode( $key_value );
            }
            $query = implode($enc_type,$new_query);
            return $query;
            
        }
    }


    public static function querystring_to_array($querystring){
        parse_str($querystring,$output);
        return $output;
    }


    public static function combine($url, $querystring){
        if(is_array($querystring)){
            $querystring = self::build_query($querystring);
        }


        if($querystring==='') return $url;

        if(strpos($url,'?') === false ){
            $url = $url.'?'.$querystring;

            return $url;
        }


        
        $url = $url.'&'.$querystring;
        return $url;
    }


    public static function host(){

        static $host=null;

        if($host !== null) return $host;

        $url = self::current_page_url();
        
        $u = new __url($url);

        $host=$u->host;

        return $host;
    }


    // host  = admin.eznewlife.com 
    // shost = eznewlife.com
    public static function shost(){
        static $shost=null;

        if($shost !== null) return $shost;

        $host = self::host();

        if(substr_count($host,'.') === 2){
            $shost = $host;
            return $shost;
        }

        $tmp = explode('.', $host);
        $lastindex = count($tmp)-1;
        $last2thindex = count($tmp)-2;
        $shost = $tmp[$last2thindex].'.'.$tmp[$lastindex];

        return $shost;
    }



    public static function server_ip(){
        if(!isset($_SERVER['SERVER_ADDR'])) return '';

        return $_SERVER['SERVER_ADDR'];
    }



    public static function client_ip(){
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
}
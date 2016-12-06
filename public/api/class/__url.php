<?php 
/*
    $url = 'http://username:password@hostname:9090/path?arg=value#anchor';
    
    Array
    (
        [scheme] => http
        [host] => hostname
        [port] => 9090
        [user] => username
        [pass] => password
        [path] => /path
        [query] => arg=value
        [fragment] => anchor
    )


    href http://username:password@hostname:9090/path?arg=value#anchor
    origin http://hostname:9090
    protocol http:
    username username
    password password
    host hostname:9090
    hostname hostname
    port 9090
    pathname /path
    search ?arg=value
    hash #anchor

*/


class __url{

    private $_data=[];

    public $querydata=[];


    public $domain;


    public function __construct($url){
        $this->url = $url;
        $this->init();
    }


    public function init(){
        $r = parse_url($this->url);
        
        foreach($r as $prop => $value){
            $this->_data[$prop] = $value;
        }


        if( isset($r['query'])){
            parse_str($r['query'], $this->querydata);
        }

        $this->hash = $this->fragment;

        $this->makeDomain();

    }



    public function __toString(){
        return $this->url();
    }



    public function toString(){
        return $this->url();
    }


    public function url(){

        $scheme = $this->scheme ? $this->scheme : 'http';
        $username = $this->user;
        $password = $this->pass;

        $userpass='';
        if($username !== '' || $password !=''){
            $userpass = $username.":".$password.'@';
        }

        $host = $this->host;

        $url=$scheme.'://'.$userpass.$host;

        if($this->port) $url.=':'.$this->port;

        $url.=$this->path;

        if($this->querydata) $url.='?'.http_build_query($this->querydata);

        if($this->hash) $url.='#'.$this->hash;

        return urldecode($url);
    }


    public function __get($prop){
        
        if($prop === 'hash') $prop = 'fragment';

        if(isset($this->_data[$prop])) return $this->_data[$prop];

        return '';
    }


    public function __set($prop, $value){
        
        if($prop === 'hash') $prop = 'fragment';

        $this->_data[$prop]=$value;
    }


    public function set($prop, $value){
        $this->querydata[$prop] = $value;
    }


    private function makeDomain(){

        $scheme = $this->scheme ? $this->scheme : 'http';

        $this->domain = $scheme.'://'.$this->host;

        if($this->port){
            $this->domain.=':'.$this->port;
        }
    }
}
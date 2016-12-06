<?php 

require __DIR__.'/autoload.php';

$conn = ff\conn();

$sql= "select * from scraping_url";

$rows = $conn->get($sql);

$dic=[];
$urls=[];

$rows=array_reverse($rows);


for ($i=0,$imax=count($rows); $i < $imax; $i++) { 
    $row=$rows[$i];
    $url = $row['url'];
    $u = new __url($url);

    if(isset($dic[$u->domain])){
        continue;
    }


    if(!urlIsOk($u->url())){
        $dic[$u->domain]=1;
        continue;
    }

    $dic[$u->domain]=1;
    $urls[]=$u->url();

}

print_r($urls);

echo '<textarea style="width:100%;height:600px;">'.json_encode($urls).'</textarea>';


function urlIsOk($url)
{
    return @file_get_contents($url);

    $headers = @get_headers($url);
    $httpStatus = intval(substr($headers[0], 9, 3));
    if ($httpStatus<400)
    {
        return true;
    }
    return false;
}

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
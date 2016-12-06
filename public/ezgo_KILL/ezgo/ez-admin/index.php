<?php 
$timestart=microtime(true);
/*
    * 壓縮 : 考慮所有情況以後 , 壓縮最好的方式是 等專案完成以後 自己copy tpl 改成一個壓縮js/css 的版本

*/
error_reporting(E_ALL);
define('NOWPATH',dirname(__FILE__).'/');
define('APPPATH',realpath(NOWPATH.'../').'/');
include(APPPATH.'config.php');
define('ROOTPATH',$config['ROOTPATH']);
define('APPDIR',str_replace(ROOTPATH,'',APPPATH));
define('URL',$config['URL']);

include_once(ROOTPATH.'ezlib3/sys.php');
$sys->init(1);
$sys->app=$config['app'];
//include('functions.php');
$sys->debug('NOWPATH = '.NOWPATH);
$sys->debug('APPPATH = '.APPPATH);
$sys->debug('ROOTPATH = '.ROOTPATH);
$sys->debug('APPDIR = '.APPDIR);
$sys->debug('URL = '.URL);

$sys->data = (object)array();


if(!empty($_COOKIE['sid']))$sid=$_COOKIE['sid'];
else $sid='';
$sys->debug($sid);
$login=false;
if($sid){
    $r = $sys->api(array(
        'api_path'=>APPPATH.'api/'
        ,'recording'=>true
        ,'onlydata'=>false
        ,'cmd'=>'login_check'
        ,'sid'=>$sid
    ));
    if($r['success']){
        $login=true;
    }
}


if(!$login){
    
    $sys->display(array(
        'tpl'=>'login.htm'
        ,'data'=>$sys->data
    ));
}else{
    
    $requireJS=$sys->requireJS('js/');
    $requireCSS=$sys->requireCSS('css/');
    //模板
    $sys->display(array(
        'tpl'=>'index.htm'
        ,'data'=>$sys->data
        ,'requireJS'=>$requireJS
        ,'requireCSS'=>$requireCSS
    ));
}





function show($text){
    echo "<pre>";
    print_r($text);
    echo "</pre>";
}

$timeend=microtime(true);
$sys->debug($timeend-$timestart);
?>
<?php
error_reporting(E_ALL);
define('NOWPATH',dirname(__FILE__).'/');
define('APPPATH',realpath(NOWPATH.'../').'/');
include(APPPATH.'config.php');
define('ROOTPATH',$config['ROOTPATH']);
define('APPDIR',str_replace(ROOTPATH,'',APPPATH));
define('URL',$config['URL']);

include_once(ROOTPATH.'ezlib3/sys.php');


if(!empty($_COOKIE['sid']))$sid=$_COOKIE['sid'];
else $sid='';

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
if($login){
    $HTTP_X_FILE_NAME = $_SERVER['HTTP_X_FILE_NAME'];
    $HTTP_X_FILE_TYPE = $_SERVER['HTTP_X_FILE_TYPE'];
    //echo $HTTP_X_FILE_NAME."\n";
    //echo $HTTP_X_FILE_TYPE."\n";
    $tmp = explode('.',$HTTP_X_FILE_NAME);
    $sub = $tmp[1];
    if(empty($sub)) $sub='.jpg';
    $sub = strtolower($sub);
    switch($sub){
        case 'bmp':
        case 'jpg':
        case 'gif':
        case 'png':
        case 'tif':
            break;
        default:
            $sub = 'jpg';
            break;
    }
    $time = time();
    $filename = $time.'.'.$sub;
    $str = file_get_contents('php://input');
    $file = APPPATH."images/".$filename;
    file_put_contents($file,$str);
    echo URL.'images/'.$filename;
    exit;
}
//file_put_contents("uploads/$f",$str);
//echo 'uploads/'.$f;
//echo $f;

?>
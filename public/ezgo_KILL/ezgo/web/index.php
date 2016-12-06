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
include(APPPATH.'functions.php');
$sys->init(1);
// $sys->debug('NOWPATH = '.NOWPATH);
// $sys->debug('APPPATH = '.APPPATH);
// $sys->debug('ROOTPATH = '.ROOTPATH);
// $sys->debug('APPDIR = '.APPDIR);
// $sys->debug('URL = '.URL);

$sys->data=(object)array();

//資料
//include('data.php');
$db = $sys->objects(array(
    'construct'=>'vdb'
    ,'db_path'=>APPPATH.'database/'
));

$page_status=0;
if(!empty($_GET['id']) && !file_exists(APPPATH.'cache/'.$_GET['id'])){
    $page_status=1;
    header("Location:".URL);
    exit;
}else if(!empty($_GET['id'])){
    $page_status=2;
    $id = $_GET['id'];
}else{
    $page_status=3;
    $id='';
}


if($page_status==3){
    $sys->display(array(
        'tpl'=>'index.htm'
    ));
}else if($page_status==2){
    
    // data 
    $data = file_get_contents(APPPATH.'cache/'.$id);
    $data = json_decode($data,true);
    
    //safebrowsing google 安全性檢查
    $url = $data[4];
    $response = safebrowsing($url);
    $safe = true;
    if($response == ''){
        $safe=true;
    }else if($response=='malware'){
        $safe=false;
    }
    
    
    
    $content = file_get_contents($url);
    // 抓 title
    preg_match('/<title>(.*)<\/title>/iU',$content,$match);
    $title = $match[1];
    
    // description
    $description='';
    preg_match('/<meta[^>]+property="og:description"[^>]+content=[\'"]([^>"\']*)[\'"][^>]+>/iU',$content,$match);
    
    if(!empty($match[1]))$description = $match[1];
    //抓meta  name="description" , name="keywords" property="og:image , og:title ,og:description, og:type
    preg_match_all('/<meta\s[^>]*>/iU',$content,$match);
    
    
    $meta = '';
    $ms = $match[0];
    $filter = array();
    $filter[]='name="description"';
    $filter[]='name=\'description\'';
    $filter[]='name="keywords"';
    $filter[]='name=\'keywords\'';
    $filter[]='property="og:image"';
    $filter[]='property=\'og:image\'';
    //$filter[]='property="og:url"';
    //$filter[]='property=\'og:url\'';
    $filter[]='property="og:title"';
    $filter[]='property=\'og:title\'';
    $filter[]='property="og:description"';
    $filter[]='property=\'og:description\'';
    $filter[]='property="og:type"';
    $filter[]='property=\'og:type\'';
    
    for($i=0,$imax=count($ms);$i<$imax;$i++){
        $m = $ms[$i];
        for($j=0,$jmax=count($filter);$j<$jmax;$j++){
            $f = $filter[$j];
            if(strpos($m,$f)!==false){
                $meta.=$m."\n";
                break;
            }
        }
    }
    
    echo $meta;
    $sys->display(array(
        'tpl'=>'single.htm'
        ,'id'=>$id
        ,'safe'=>$safe
        ,'meta'=>$meta
        ,'title'=>$title
        ,'url'=>$url
        ,'description'=>$description
    ));
}

$timeend=microtime(true);
echo "\n<!--".($timeend-$timestart)."-->";
//$sys->debug($timeend-$timestart);
?>
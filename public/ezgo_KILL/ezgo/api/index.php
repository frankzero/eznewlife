<?php
    error_reporting(E_ALL);
    define('NOWPATH',dirname(__FILE__).'/');
    define('APPPATH',realpath(NOWPATH.'../').'/');
    include(APPPATH.'config.php');
    define('ROOTPATH',$config['ROOTPATH']);
    define('APPDIR',str_replace(ROOTPATH,'',APPPATH));
    define('URL',$config['URL']);
    
    include_once(ROOTPATH.'ezlib3/sys.php');
    $sys->init(0);
    $sys->app=$config['app'];
    $sys->debug($_POST);
    $p = (empty($_POST['param']))?array():$_POST['param'];
    
    $p['cmd'] = $_POST['cmd'];
    $p['api_path'] = APPPATH.'api/';
    $p['recording'] = true;
    $p['onlydata'] = false;
    $r = $sys->api($p);
    
    echo "\n".json_encode($r);
?>
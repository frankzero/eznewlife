<?php
error_reporting(E_ALL);

require __DIR__.'/../autoload.php';

api_handle();




function api_handle(){

    $cmd = http::post('cmd');

    if(!$cmd) return;

    $file = __APP__.'api/'.$cmd.'.php';



    if( !file_exists($file) ) return;

    require $file;



    $class_name = 'api_'.$cmd;

    $o = new $class_name();
    $data = $o->call();
    
    echo '/*****/'.json_encode($data).'/*****/';

}
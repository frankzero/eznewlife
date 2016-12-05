<?php 


if(! function_exists('myAutoLoader') ){

    require __DIR__.'/class/__mypdo.php';
    require __DIR__.'/function.php';
    require __DIR__.'/gfunction.php';
    //require __DIR__.'/../app/helpers.php';


    function myAutoLoader( $class_name ){
        //$file = find_autoload_file($class_name);
        $file = __DIR__.'/class/'.$class_name.'.php';

        if(!file_exists($file)) return;

        require $file;
        
    }
    spl_autoload_register( 'myAutoLoader' );


    if(!defined('__APP__')) define('__APP__', __DIR__.'/../');
    if(!defined('__ROOT__'))define('__ROOT__', __DIR__.'/../public/');


    call_user_func(function(){
        if (!isset($_SERVER['SERVER_NAME'])) {
            define('__DOMAIN__', 'eznewlife.com');
            return;
        }

        $domain = $_SERVER['SERVER_NAME'];

        if ($domain == "dark.eznewlife.com") {
            define('__DOMAIN__', $domain);
            return;
        }

        if ($domain == "dark.getez.info") {
            define('__DOMAIN__', $domain);
            return;
        }


        $tmp = explode('.', $domain);

        

        if (count($tmp) === 2) {
            define('__DOMAIN__', $domain);
            return;
        }

        $domain = $tmp[1] . '.' . $tmp[2];
        define('__DOMAIN__', $domain);
        return;
    });

    //var_dump( cc('site_id') );


}
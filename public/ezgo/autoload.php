<?php 

if( !function_exists('autoLoader') ){

    define('__ROOT__',  realpath(__DIR__.'/../').'/');

    define('__APP__', __DIR__.'/');

    define('__STORAGE__', __APP__.'storage/');


    //define('app', str_replace('/', '', str_replace(__ROOT__, '', __APP__) ) );


    require __DIR__.'/function.php';
    require __DIR__.'/class/http.php';
    require __DIR__.'/../../frank/autoload.php';
    
    function autoLoader( $class_name ){

        $file = __APP__.'class/'.$class_name.'.php';

        require $file;
    }

    spl_autoload_register( 'autoLoader' );
}


    
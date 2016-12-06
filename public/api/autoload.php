<?php 

require __DIR__.'/simple_html_dom.php';
require __DIR__.'/function.php';

define("MEDIAWIKI_PATH", __DIR__.  "/mediawiki-1.26.2/");
define("__API__", __DIR__.'/');

require __DIR__.'/mediawiki-1.26.2/autoload.php';
require __DIR__.'/mediawiki-1.26.2/mediawiki-zhconverter.inc.php';
require __DIR__.'/../../frank/autoload.php';


spl_autoload_register(function ($class_name) {
    $file = __DIR__."/class/$class_name.php";

    if(file_exists($file)) {
        require $file;
        return;
    }

    if( strpos($class_name, '\\') !== false ){
        $c = explode('\\', $class_name);
        $ns = $c[0];
        $cs = $c[1];
        $file = __API__.'module/'.$ns.'/'.$cs.'.php';
        if(file_exists($file)){
            require $file;
            return;
        }
    }

    //echo 'class not exist '.$class_name;
});


<?php
//setcookie('debug', 'frank', time()+(60*60*24*30) );echo 'ok';exit;

//$ms = memory_get_usage();
//$ts = microtime(true);
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/



require __DIR__.'/../frank/autoload.php';

$device = new device();
$m = $device->is_mobile();
$url = ff\current_page_url();
$url=$url.$m;
$filename=hash('sha512', $url).'.html';
$static_file =  __DIR__.'/cache/'.$filename;

$restatic_file = __DIR__.'/recache/'.$filename;

$b = cache_file($static_file, $restatic_file, $url);

if($b===true){
    // setcount(true, $m);
    exit;
}

// setcount(false, $m);
//echo $html;


function cache_file($static_file, $file, $url){
    return false;
    if(!file_exists($file)){
        file_put_contents($file, $url);
    }
    if(file_exists($static_file)){
       //echo date('Y-m-d H:i:s', filemtime($static_file)). ' '.date('Y-m-d H:i:s', time());
//echo filesize($static_file);
        if(filesize($static_file) === 0){
            unlink($static_file);
            return false;
        }
        $filetime = filemtime($static_file);
        if( (time() - $filetime) > 3600 ){
            unlink($static_file);
            return false;
        }

        echo file_get_contents($static_file);

        echo "\n";echo '<!--cache '.date('H:i:s').'-->';
        
        return true;
    }
    return false;
}


function setcount($bool=false, $m){
    $file = __DIR__.'/../storage/count/';

    if($bool){
        $file = $file.'cache'.$m.'.txt';
        file_put_contents($file, '.', FILE_APPEND);
        return;
    }

    $file = $file.'nocache'.$m.'.txt';
    file_put_contents($file, '.', FILE_APPEND);

}


require __DIR__.'/../bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);


///$te = microtime(true);

//$me = memory_get_usage();


// echo '<!--'.($te-$ts).'-->';
// echo '<!--'.mmmmmformat($me-$ms).'-->';





function mmmmmformat($mem_usage){ 
    $r='';

    if ($mem_usage < 1024) 
        $r.=$mem_usage." bytes"; 
    elseif ($mem_usage < 1048576) 
        $r.=round($mem_usage/1024,2)." KB"; 
    else 
        $r.=round($mem_usage/1048576,2)." MB"; 
        
    return $r;
}
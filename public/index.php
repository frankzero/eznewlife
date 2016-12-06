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



//if( strpos($_SERVER['REQUEST_URI'], 'eznewlife.com:80') !== false ){
if( strpos($_SERVER['HTTP_USER_AGENT'], 'FBAN/FBIOS') !== false 
    && $_SERVER['HTTP_REFERER'] === 'http://m.facebook.com'
    ){
    //file_put_contents(__DIR__.'/../storage/frank_test', '');
    //file_put_contents(__DIR__.'/../storage/frank_test', print_r($_SERVER,true)."\n", FILE_APPEND);

    // FB BROWSER 靈異事件  REQUEST_URI 會帶上整個網址 導致 ROUTE NOT FOUND 
    $_SERVER['REQUEST_URI'] = str_replace('http://eznewlife.com:80', '', $_SERVER['REQUEST_URI']);
}





if($_SERVER['REQUEST_METHOD'] === 'POST' and $_SERVER["SERVER_NAME"]==="eznewlife.com"){
	exit;
    $file = __DIR__.'/../storage/test';
    $r = '';
    $r = print_r($_SERVER, true);
    $r .= print_r($_GET, true);
    $r .= print_r($_POST, true);
    $r.="===========================================================\n"; 
    file_put_contents($file, $r, FILE_APPEND);  
}



function secure3(){
    
    static $r = null;

    if($r !== null) return $r;

    if( isset($_SERVER['HTTP_X_FORWARDED_PROTO'])  && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'){
        $r = true;
        return $r;
    }


    if( isset($_SERVER['REQUEST_SCHEME'])  && $_SERVER['REQUEST_SCHEME'] === 'https'){
        $r = true;
        return $r;
    }

    $r = false;
    return $r;
}


secure3();



require __DIR__.'/../frank/autoload.php';
$fcache = fcache::make();

$fcache->response();


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

$fcache->log();


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
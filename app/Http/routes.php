<?php


use App\Http\Controllers\Controller\ArticleController;

use App\Article, App\Category;

//use app\Http\Controllers\ArticleController;



$_domain = function ($define_host) {


    if (!isset($_SERVER['SERVER_NAME'])) return $define_host;

    $domain = $_SERVER['SERVER_NAME'];
    //echo $domain;

    if (strpos($domain, 'www.') !== false) {
        //$domain = str_replace('www.', '', $domain);

        //exit;
        $current_page_url = function () {
            static $current_page_url = null;
            if (null === $current_page_url) {
                $current_page_url = 'http';
                if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
                    $current_page_url .= "s";
                }
                $current_page_url .= "://";
                if ($_SERVER["SERVER_PORT"] != "80") {
                    $current_page_url .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"]; // REQUEST_URI ,PHP_SELF
                } else {
                    $current_page_url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
                }
                $current_page_url = explode('?', $current_page_url);
                $current_page_url = $current_page_url[0];
                $current_page_url = urldecode($current_page_url);
            }
            return $current_page_url;
        };

        $url = $current_page_url();
        $url = str_replace('www.', '', $url);
        header("Location:" . $url);
        exit;

    }


    if (strpos($domain, $define_host) === false) return $define_host;
    if ($domain == "dark.eznewlife.com") {
        return "eznewlife.com";
    }
    return $domain;
};

/*
if (Config::get('app.sql_log')===true){
Event::listen('illuminate.query', function ($query, $params, $time, $conn) {
    $txt = date("Y-m-d H") . ".txt";
    $exists = Storage::has($txt);
    if ($exists) {
        $size = Storage::size($txt); //dd($size);
        if ($size > 102400) Storage::delete($txt);

    }
	  $agent = new Jenssegers\Agent\Agent();
        if ($agent->isRobot()) {
			$robot="機器人";
			return;
		}else{
			$robot='';
		}
	//$a=DB::getConnection();
	//$queries = DB::getQueryLog();var_dump(['aaaa',$queries]);
    if (!strpos(Request::url(), "/admin")) {
        if (!empty($query)) {
            Storage::prepend($txt, date("Y-m-d H:i:s") . " " . Request::url() ."\n".$conn. "(".$robot."/".$agent->device().")"  ."". "\n". $query . "(" 
			. implode("|", $params) . ") (" . $time . ")\n"
            );
        }
    }
});
}




Route::get('sql_log', function () {
	//echo $_GET['hr'];
    if (!empty($_GET['hr'])){
		$txt =date("Y-m-d H",time()+3600*$_GET['hr']). ".txt";
	}else {
		$txt = date("Y-m-d H") . ".txt";
	}
	//echo $txt;
	$exists= Storage::has($txt);
	if ($exists) {
		$contents = Storage::get($txt);
		$contents = "<pre>" . $contents . "</pre>";
	}
    // $contents = Storage::get('route.txt');
    echo $contents;
});
*/



if(__DOMAIN__==='getez.info'){
    require __DIR__.'/domain/dark_eznewlife_com.php';
    require __DIR__.'/domain/getez_info.php';

}else if(__DOMAIN__==='eznewlife.com'){
    require __DIR__.'/domain/dark_eznewlife_com.php';
    require __DIR__.'/domain/eznewlife_com.php';

}else if(__DOMAIN__==='dark.eznewlife.com'){

    require __DIR__.'/domain/dark_eznewlife_com.php';

}else if(__DOMAIN__==='dark.getez.info'){

    require __DIR__.'/domain/dark_getez_info.php';

}else if(__DOMAIN__==='avbody.info'){

    require __DIR__.'/domain/avbody_info.php';

}else{
    $file = __DIR__.'/domain/'.str_replace('.', '_', __DOMAIN__).'.php';

    if(file_exists($file)){
        require $file;
    }
    
}





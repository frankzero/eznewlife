<?php 

require __DIR__.'/autoload.php';

url_debug_handle();

function url_debug_handle(){
    $conn=ff\conn();

    $url = http::get('url');

    if(!$url) return 0;

    $r = facebookDebugger($url);

    print_r($r);

}


function facebookDebugger($url){
    $graph = 'https://graph.facebook.com/';
    $post = 'id='.urlencode($url).'&scrape=true';
       
    $r = curl_init();
    curl_setopt($r, CURLOPT_URL, $graph);
    curl_setopt($r, CURLOPT_POST, 1);
    curl_setopt($r, CURLOPT_POSTFIELDS, $post);
    curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($r, CURLOPT_CONNECTTIMEOUT, 5);
    $data = curl_exec($r);
    curl_close($r);
    return $data;
}
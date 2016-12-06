<?php 

require __DIR__.'/autoload.php';


echo url_used_handle();


function url_used_handle(){
    $conn=ff\conn();

    $url = http::post('url');

    if(!$url) return 0;

    $sql="select id from scraping_url WHERE url=:url";
    $row=$conn->getOne($sql, $url);

    if($row===false) return 0;

    return 1;

}


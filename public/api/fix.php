<?php 
exit;
require __DIR__.'/autoload.php';

echo fixData();



function fixData(){
    $conn=oconn('M');
    $sql="select * from scraping_url";

    $rows = $conn->get($sql);

    for ($i=0,$imax=count($rows); $i < $imax; $i++) { 
        $r=$rows[$i];
        $url = $r->url;    
        echo $i.' '.$r->url."\n";

        if( strpos($url, 'mega-shares') !== false) continue;
        if( strpos($url, 'anyelse') !== false) continue;

        if(!empty($r->title)) continue;

        $u = getUrlConfig($r->url);

        if(!$u) continue;


        $match_title = $u[1];

        $html='';
        try{
            $html = @__get_html($url);
        }catch(Execption $e){

        }

        if($html === '') continue;
        
        
        $title = __find_title($html, $match_title, $url);

        if(!$title) continue;

        //echo $title."\n";

        $sql="update scraping_url SET title=:title WHERE id=".$r->id;

        echo $sql."\n";
        $conn->update($sql, $title);

    }

    //print_r($rows);

}


function url_used_handle(){
    $conn=ff\conn();

    $url = http::post('url');

    if(!$url) return 1;

    $sql="select id from scraping_url WHERE url=:url";
    $row=$conn->getOne($sql, $url);

    if($row!==false) return 1;


    $u = getUrlConfig($url);
    if(!$u) return 1;

    $match_title = $u[1];



    $html = __get_html($url);
    
    $title = __find_title($html, $match_title, $url);

    if($title!==''){
        $sql="select id from scraping_url WHERE title=:title";
        $row=$conn->getOne($sql, $title);

        if($row!==false) return 1;
    }

    

    return 0;

}




function __get_html($url){

    $host = parse_url($url, PHP_URL_HOST);

    $class_name = 'scraping_'.str_replace('.', '_', $host);

    if(class_exists($class_name)){
        $scraping = new $class_name($url);
    }else{
        $scraping = new scraping($url);
    }

    return $scraping->content();
}

function __find_title($html, $match_title, $url){
    $host = parse_url($url, PHP_URL_HOST);
    $class_name = 'find_title_'.str_replace('.', '_', $host);

    if(class_exists($class_name)){
        $find_title = new $class_name($html, $match_title);
    }else{
        $find_title = new find_title($html, $match_title);
    }

    return $find_title->getTitle();
}
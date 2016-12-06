<?php 

require __DIR__.'/autoload.php';


readmore_handle();



function readmore_handle(){
    $url = http::post('url');

    if(!$url) return;

    // echo my_file_get_contents($url);return;

    $query = file_get_html( my_file_get_contents($url) );

    $title = $query->find('title');

    $title = $title[0]->innertext;

    $og_image = $query->find('meta[property=og:image]');

    $og_image = $og_image[0];

    $image_url = $og_image->getAttribute('content');


    $data=new stdClass();
    $data->title = $title;
    $data->image_url=$image_url;

    echo '/*****/'.json_encode($data).'/*****/';

}
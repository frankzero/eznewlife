<?php 
function safebrowsing($url){
    $url = urlencode($url);
    $content = file_get_contents('https://sb-ssl.google.com/safebrowsing/api/lookup?client=api&apikey=ABQIAAAA94J8Im19w8Zu7Abx6_ISmhQT7cfA_8eCKSsC3DGBKR4xc9hT4A&appver=1.0&pver=3.0&url='.$url);
    return $content;
}

function show($text){
    echo "<pre>";
    print_r($text);
    echo "</pre>";
}
?>
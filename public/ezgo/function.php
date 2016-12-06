<?php 



function safebrowsing($url){
    $url = urlencode($url);
    $content = file_get_contents('https://sb-ssl.google.com/safebrowsing/api/lookup?client=api&apikey=ABQIAAAA94J8Im19w8Zu7Abx6_ISmhQT7cfA_8eCKSsC3DGBKR4xc9hT4A&appver=1.0&pver=3.0&url='.$url);
    return $content;
}




function pre($text){
    echo "<pre>";
    print_r($text);
    echo "</pre>";
}



function qrcode($url){
    $url = 'http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl='.urlencode($url).'&chld=H|0';
    $content = file_get_contents($url);
    return $content;
}



function client_path($path){
    return '/'.str_replace(__ROOT__, '', $path);
}



function is_mobile(){

    static $is_mobile = null;

    if($is_mobile !== null) return $is_mobile;

    $d = new device;

    if($d->device_code === 1){
        $is_mobile=1;
        return $is_mobile;
    }

    $is_mobile=0;
    return $is_mobile;
}
<?php 
require __DIR__.'/autoload.php';

// echo MediaWikiZhConverter::convert("大家有没有发现，周润发已经成了自拍界的集邮天王，走到哪儿就跟人自拍到哪儿", "zh-tw");//轉台灣繁體
// echo MediaWikiZhConverter::convert("記憶體", "zh-cn");//轉大陸簡體
// echo MediaWikiZhConverter::convert("罗纳尔多", "zh-hk");//轉香港繁體
// exit;
define('UPLOADS', __DIR__.'/../uploads/');

//print_r($_POST);

// $class_name = '\\www_akjunshi_com\\scraping';
// new $class_name('qq');


api_handle();


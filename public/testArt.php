<?php 

require __DIR__.'/../frank/autoload.php';

echo "<pre>";


$ac = new articleCollection();

$ac->page=2;
//$ac->dir='ASC';

$as = $ac->query();

//echo count($as);
print_r($as);



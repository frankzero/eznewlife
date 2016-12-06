<?php 
    $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    

    if(!isset($_GET['tt'])){
        $url.='?tt='.time();
    }
    



    require __DIR__.'/test.html';
?>

<?php 
if(!empty($_GET['qr']) && file_exists('ezgo/qr/'.$_GET['id'])){
	echoimage('ezgo/qr/'.$_GET['id']);
	exit;
}else{
	include('web/index.php');
}


function echoimage($file){
    header('content-type: image/jpeg');
    readfile($file);
}
?>
<?php
$is_admin=false;
if(!empty($_COOKIE['nom']) && $_COOKIE['nom']=='nomA123999') $is_admin=true;

if($is_admin && $_POST['act']=='clearcache'){
    $files = glob(dirname(__FILE__).'/static/*'); // get all file names
    foreach($files as $file){ // iterate files
      if(is_file($file))
        unlink($file); // delete file
    }
}

<?php 

require __DIR__.'/frank/autoload.php';

$conn=ff\conn();

$now = date('Y-m-d H:i:s');
$sql = "update `articles` set `status` = '1' where `status` = '2' and `publish_at` < '$now' and `deleted_at` is null";
echo $conn->update($sql);

 
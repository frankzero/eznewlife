<?php 

print_r($_SERVER);
print_r($_COOKIE);

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    print_r($_GET);
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    print_r($_POST);
}

print_r(file_get_contents('php://input', false , null, -1));


print_r(getallheaders());
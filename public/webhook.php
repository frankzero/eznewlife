<?php 

$token='EAAOPBVUpZCtkBAA1gyhIZBWS50fSLXiB9qPykLWlbBDLDnsInXJSSbRDyD0mDuwZAM0mWJvZB011Ab3KdPa97pBlXfjRC7itZC2uG6H8msUtfvy0ygZBPPZAy13MCcQAgNsdGK30hHwD4XiG8GJeStZB5j78aFzmhNEYNgWGIQZCZC1QZDZD';

if(isset($_GET['hub_verify_token']) && $_GET['hub_verify_token'] === $token){
    echo $_GET['hub_challenge'];
}else{
    echo 'Error, wrong validation token';
}
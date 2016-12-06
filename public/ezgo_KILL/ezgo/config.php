<?php
    $config=array();
    $config['site']='ezgo';
    $config['app']='ezgo';
    $config['ROOTPATH']='/var/www/vhosts/ezgo.cc/httpdocs/';
    $config['URL']='http://ezgo.cc/';
    
    $config['adminip'] = array('114.33.43.184','59.126.99.165','59.126.206.35'); // 工程師ip  debug用
    
    $config['timeout']=3600; //後台幾秒沒動作就踢出
    $config['users']=array(); //使用者帳號密碼
    $config['users'][]='nom,nomA123999';
    $config['users'][]='frank,10281028';
    
    /*
    $config['app_id']='160915094095445'; //319399984849437
    $config['admins']='100000103508966';
    $config['secret']='1c1db3f1c68ff3e6753aa910d79f6e52';
    */
?>
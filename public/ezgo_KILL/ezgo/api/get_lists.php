<?php 
if(!$this) exit();
$api_message = '儲存檔案';
if(empty($_COOKIE['sid'])) $sid='';
else $sid = $_COOKIE['sid'];

if(!$sid){
    $response=$this->ret(0,'login fail','');
}else{
    $db = $this->objects(array(
        'construct'=>'vdb'
        ,'db_path'=>APPPATH.'database/login/'
    ));
    $db->load($sid);
    if(!$db->data){
        $response=$this->ret(0,'login fail','');
    }else{
        //登入成功
        $db = $this->objects(array(
            'construct'=>'vdb'
            ,'db_path'=>APPPATH.'database/'
        ));
        $db->load('file_lists');
        $response=$this->ret(1,'',$db->data);
    }
}
return $response;
?>
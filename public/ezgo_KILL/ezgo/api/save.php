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
        $d = array();
        $d['file'] = $p['file'];
        $d['title'] = $p['title'];
        $d['description'] = $p['description'];
        $d['hidecontent']=$p['hidecontent'];
        $d['ogimage']=$p['ogimage'];
        
        $d['file'] = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $d['file']);
        if($d['file']){
            $file = $this->objects(array(
                'construct'=>'vdb'
                ,'db_path'=>APPPATH.'database/'
            ));
            $file->load($d['file']);
            $file->data = $d;
            $file->save();
            
            // file_list
            $file->load('file_lists');
            if(!in_array($d['file'],$file->data)){
                $file->data[]=$d['file'];
                $file->save();
            }
            $response=$this->ret(1,'','');
        }else{
            $response=$this->ret(0,'file name not correct','');
        }
    }
}
return $response;
?>
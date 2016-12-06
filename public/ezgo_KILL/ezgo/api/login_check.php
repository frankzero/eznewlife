<?php 
if(!$this) exit();
$api_message = 'admin 確認登入';
$sid = $p['sid'];

if(!empty($this->config['timeout'])) $timeout=$this->config['timeout'];
else $timeout=3600;

if(!$sid){
    $response=$this->ret(0,'login fail','');
}else{
    $db = $this->objects(array(
        'construct'=>'vdb'
        ,'db_path'=>APPPATH.'database/login/'
    ));
    $db->load($sid);
    
    //echo ((time()-$db->time()));
    if(!$db->data){
        $response=$this->ret(0,'login fail','');
    }else{
        if( (time()-$db->time()) >$timeout ){ 
            $db->delete();
            $response=$this->ret(0,'login fail','');
        }else{
            $db->save();
            $response=$this->ret(1,'',$db->data);
        }
    }
}
return $response;
?>
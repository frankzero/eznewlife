<?php 
if(!$this) exit();
$api_message = 'admin 登入';

$user=$p['user'];
$password=$p['password'];


$bool=false;
$users = $this->config['users'];
for($i=0,$imax=count($users);$i<$imax;$i++){
    $u = $users[$i];
    if(trim($u)===$user.','.$password){
        $bool=true;
        break;
    }
}
if($bool===true){
    $sid=$this->unique_id(20);
    $r=array();
    $r['sid']=$sid;
    $this->debug(APPPATH.'database/login/');
    $db=$this->objects(array(
        'construct'=>'vdb'
        ,'db_path'=>APPPATH.'database/login/'
    ));
    $db->load($sid);
    $db->data=$user;
    $db->save();
    $response = $this->ret(1,'',$r);
}else{
    $response = $this->ret(0,'login fail','');
}

return $response;
?>
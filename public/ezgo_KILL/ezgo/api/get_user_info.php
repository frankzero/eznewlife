<?php 
if(!$this) exit();
$api_message = 'admin 取得使用者資料';
$sid=$_COOKIE['sid'];


//確認登入
$r = $this->api(array(
    'api_path'=>APPPATH.'api/'
    ,'recording'=>true
    ,'onlydata'=>false
    ,'cmd'=>'login_check'
    ,'sid'=>$sid
));

if($r['success']){
    $conn = $this->getconntioni($this->app);
    $user_data = $r['data'];
    $id=$user_data['admin_id'];
    $sql = "select id,user,name,email from admin where id=$id";
    $data=array();
    $rs =$conn->query($sql);
    while($row=$rs->fetch_assoc()){
        $data=$row;
    }
    $rs->close();
    $response = $this->ret(1,'',$data);
    $conn->close();
}else{
    $response = $r;
}

return $response;
?>
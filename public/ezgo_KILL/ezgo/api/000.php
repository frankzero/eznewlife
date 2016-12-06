<?php exit();?>

<?php 
if(!$this) exit();
$api_message = '顯示影片首頁';
$sid=$_COOKIE['sid'];
$password=  $p['password'];
$name=  $p['name'];
$email=  $p['email'];
$enable=  $p['enable'];


//確認登入
$r = $this->api(array(
    'api_path'=>APPPATH.'api/'
    ,'recording'=>true
    ,'onlydata'=>false
    ,'cmd'=>'login_check'
    ,'sid'=>$sid
));

if($r['success']){
    $conn = $this->getconntioni('funvdo');
    
    $search = $conn->real_escape_string($search);
    
    $conn->close();
    $response = $this->ret(1,'',$data);
}else{
    $response = $r;
}

return $response;
?>

<?php 
if(!$this) exit();
$api_message = 'admin 確認登入';
$sid = $p['sid'];

$conn = $this->getconntioni('funvdo');

$sql = "select id,admin_id,user,ip from login_admin WHERE sid=?";
$stmt = $conn->prepare($sql);
if(!$conn->error){
    $stmt->bind_param('s',$sid);
    $stmt->execute();
    $rs = $stmt->get_result();
    $user_data = $rs->fetch_assoc();
    $rs->close();
    if(count($user_data)==0){
        $response = $sys->ret(0,'sid error',array());
    }else{
        $sql ="UPDATE login_admin SET date_active='".date('Y-m-d H:i:s')."' WHERE id='".$user_data['id']."'";
        $sys->query($sql);
        $response = $sys->ret(1,'',$user_data);
    }
    $stmt->close();
}else{
    $this->debug($conn->error);
    $response = $this->ret(1,'',$data);
}
$conn->close();
return $response;
?>


<?php 
$api_message = 'iconn bind_param 範例';

$fromid = $p['fromid'];
$post_id = $p['post_id'];
$time = $p['time'];
$text = $p['text'];
$object_id = $p['object_id'];
$post_fbid = $p['post_fbid'];
$name = $p['name'];
$pic_small = $p['pic_small'];
$pic_square = $p['pic_square'];
$pic = $p['pic'];
$total_count = $p['total_count'];
$video_url = $p['video_url'];
$video_title = $p['video_title'];
$youtube_id = $p['youtube_id'];
$fbid = $p['id'];
$ip = $this->getip();
$enable=1;
if(strpos($p['text'],'http://')!==false){
    $enable=0;
}


$conn = $this->getconntioni('funvdo');
$sql="INSERT INTO new_comment SET ";
$sql.="fromid=?";
$sql.=",post_id=?";
$sql.=",time=?";
$sql.=",text=?";
$sql.=",object_id=?";
$sql.=",post_fbid=?";
$sql.=",name=?";
$sql.=",pic_small=?";
$sql.=",pic_square=?";
$sql.=",pic=?";
$sql.=",total_count=?";
$sql.=",video_url=?";
$sql.=",video_title=?";
$sql.=",youtube_id=?";
$sql.=",fbid=?";
$sql.=",ip=?";
$sql.=",enable=?;";

$stmt = $conn->prepare($sql);
if(!$conn->error){
    $stmt->bind_param('sssssssssssssssss'
    ,$fromid
    ,$post_id
    ,$time
    ,$text
    ,$object_id
    ,$post_fbid
    ,$name
    ,$pic_small
    ,$pic_square
    ,$pic
    ,$total_count
    ,$video_url
    ,$video_title
    ,$youtube_id
    ,$fbid
    ,$ip
    ,$enable
    );
    $stmt->execute();
    $stmt->close();
    $response = $this->ret(1,'','');
}else{
    $this->debug($conn->error);
    $response = $this->ret(0,'','');
}
$conn->close();
return $response;
?>


<?php 
if(!$this) exit();
$api_message = '取得隨機熱門影片';
$max = $p['max'] OR 6;
$not_in_array = $p['not_in_array'] OR array();

$conn = $this->getconntioni('funvdo');
$data = array();

$where_exclude_id =(sizeof($not_in_array)==0) ? "1" : "`id` NOT IN(".implode(',',$not_in_array).")";

$sql="SELECT * FROM  `video` WHERE `enable`='Y' AND `is_hot`='Y' AND ($where_exclude_id) ORDER BY RAND() LIMIT 0,$max"; 
$rs = $conn->query($sql);
if(!$conn->error){
    while($row = $rs->fetch_assoc()){
        $data[]=$row;
    }
    
}else{
    $this->debug($conn->error);
}
$response = $this->ret(1,'',$data);
$conn->close();
return $response;
?>
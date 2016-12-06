<?php 
if(!$this) exit();
$api_message = 'shorturl';

$url = $p['url'];

if(strpos($url,'http')!==0){
    $url='http://'.$url;
}
$conn = $this->getconnectioni($this->config['app']);

$is_insert = false;
$data = array();
// 1. db 
$sql = "select * from dictionary where url=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s',$url);
$stmt->execute();
$stmt->bind_result($id,$_url,$key);
$stmt->fetch();
$stmt->close();

if(!empty($id)){
    $is_insert=false;
    $qr = URL.'qr/'.$key.'.jpg';
    $surl = URL.$key;
    $data = array($id,$surl,$key,$qr,$url);
}else{
    $is_insert=true;
    $key = $this->unique_id(6);
    $sql = "INSERT INTO `dictionary` SET `url`=?,`key`=?";
    $stmt = $conn->prepare($sql);
    $this->debug($conn->error);
    $stmt->bind_param('ss',$url,$key);
    $stmt->execute();
    $stmt->close();
    
    
    $qr = URL.'qr/'.$key.'.jpg';
    $surl = URL.$key;
    $data = array($conn->insert_id,$surl,$key,$qr,$url);
}

// 2. qrcode
if($is_insert===true || true){
    $fn = new shorturl();
    $r = $fn->qrcode($surl);
    file_put_contents(APPPATH.'qr/'.$key.'.jpg',$r);
}

// 3. cache
if($is_insert===true || true){
    file_put_contents(APPPATH.'cache/'.$key,json_encode($data));
}

$conn->close();
$response = $this->ret(1,'',$data);
return $response;

class shorturl{
    public function __construct(){
        
    }
    
    public function qrcode($str){
        $url = 'http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl='.urlencode($str).'&chld=H|0';
        $content = file_get_contents($url);
        return $content;
    }
}

?>
<?php 
if(!$this) exit();
$api_message = '將變數轉成檔案使用';
$sid=$_COOKIE['sid'];

$file=$p['file'];
$method=$p['method'];
//確認登入
$r = $this->api(array(
    'api_path'=>APPPATH.'api/'
    ,'recording'=>true
    ,'onlydata'=>false
    ,'cmd'=>'login_check'
    ,'sid'=>$sid
));
if($r['success']){
    $db = $this->objects(array(
        'construct'=>'vdb'
        ,'db_path'=>APPPATH.'database/'
    ));
    $db->load($file);
    switch($method)
    {
        case 'load':
            $db->load($file);
            break;
        case 'set':
            $db->data[$p['key']] = $p['val'];
            $db->save();
            break;
        case 'setall':
            $db->data = $p['val'];
            $db->save();
            break;
        case 'remove':
            unset ($db->data[$p['key']]);
            $db->save();
            break;
        case 'append':
            $db->data[]=$p['val'];
            $db->save();
            break;
        case 'list_set':
            $key = $p['key'];
            $val = $p['val'];
            for($i=0,$imax=count($db->data);$i<$imax;$i++){
                $d =$db->data[$i];
                if($d[$key] == $val[$key]){
                    $db->data[$i]=$val;
                }
            }
            $db->save();
            break;
        case 'list_remove':
            $new_arr = array();
            for($i=0,$imax=count($db->data);$i<$imax;$i++){
                $d = $db->data[$i];
                if($d[$key]!=$val){
                    $new_arr[] = $d;
                }
            }
            $db->data = $new_arr;
            $db->save();
            break;
        case 'clear':
            $db->data=array();
            $db->save();
            break;
        default:
            break;
    }
    
    $response = $this->ret(1,'',$db->data);
}else{
    $response = $r;
}

return $response;
?>
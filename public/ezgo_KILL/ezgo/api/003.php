<?php 
if(!$this) exit();
$api_message = 'api_log';


$files = glob(APPPATH.'database/*');
$data=array();
for($i=0,$imax=count($files);$i<$imax;$i++)
{
    $f=$files[$i];
    if(strpos($f,'apidescription_')!==false){
        $r = file_get_contents($f);
        $r = json_decode($r,true);
        $f = explode('apidescription_',$f);
        $r['file']=$f[1];
        $data[]=$r;
    }
}
//$this->debug($data);
$response = $this->ret(1,'',$data);

return $response;
?>
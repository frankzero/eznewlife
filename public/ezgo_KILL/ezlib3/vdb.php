<?php
/*
$db = $this->objects(array(
    'construct'=>'vdb'
    ,'db_path'=>APPPATH.'database/'
));
$db->load('video_ids');
$i=count($db->data)-1;
$db->save();
*/

class vdb
{
    private $sys;
    private $file;
    private $db_path;
    public $data;
    public $debug;
    public function __construct($p){
        $this->sys = $GLOBALS['sys'];
        $this->db_path=$p['db_path'];
        
        if(!empty($p['debug'])) $this->debug=$p['debug'];
        else $this->debug = true;
    }
    public function load($file){
        $this->file = $this->db_path.$file;
        if($this->debug) $this->sys->debug('db : '.$file);
        if(file_exists($this->file)){
            $str = file_get_contents($this->file);
            if($this->is_json($str)){
                $this->data = json_decode($str,true);
            }else{
                $this->data = $str;
            }
        }else{
            $this->data = array();
        }
        return $this;
    }
    public function save(){
        if(is_array($this->data))
            $str=json_encode($this->data);
        else{
            $str=$this->data;
        }
        file_put_contents($this->file,$str);
        return $this;
    }
    public function delete(){
        //移除檔案
        unlink($this->file);
        return $this;
    }
    public function exists(){
        return file_exists($this->file);
    }
    public function time(){
        if(file_exists($this->file)){
            return filemtime($this->file);
        }else{
            return 0;
        }
    }
    private function is_json($str){
        $str = trim ($str); //去除頭尾空白
        $s = substr($str, 0,1);
        $e = substr($str, -1);
        if( ($s=='{'&& $e=='}') || ($s=='[' && $e==']') ){
            return true;
        }else{
            return false;
        }
    }
}
?>
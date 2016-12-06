<?php 
class iconn extends mysqli
{
    public function __construct($host,$user,$dbpwd,$dbname){
        $this->link = $this->mysqli($host,$user,$dbpwd,$dbname);
        $this->set_charset('utf8');
        $this->sys = $GLOBALS['sys'];
        $this->log = true;
    }
    public function query($sql){
        if($this->log) $this->sys->debug($sql);
        $this->sys->sqls[]=$sql;
        $this->result = parent::query($sql);
        
        if($this->error){
            $this->sys->debug($this->error);
        }
        return $this->result;
    }
    public function prepare($sql,$sql2=''){
        // sql2 拼好的 sql 用來debug
        if($this->log) $this->sys->debug($sql);
        if($sql2=='') $this->sys->sqls[]=$sql;
        else $this->sys->sqls[]=$sql2;
        return parent::prepare($sql);
    }
    private $conn;
    private $sys;
    private $stmt;
    private $link;
    private $result;
    public $log;
}
?>
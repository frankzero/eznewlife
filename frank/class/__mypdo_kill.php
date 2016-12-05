<?php 


class __db extends PDO{}


abstract class ___db extends __db{
    
    public abstract function get($arguments, $fetch='FETCH_ASSOC');
    
    public abstract function getOne($arguments, $fetch='FETCH_ASSOC');
    
    public abstract function update($arguments);
    
    public abstract function insert($arguments);
    
    public abstract function delete($arguments);
    
    public function q($arguments){
        
        if( !is_array($arguments) ){
            $sql = $arguments;
        }else{
            $sql = $arguments[0];
        }
        
        $sql = trim($sql);
        
        $qtype = explode(' ',$sql);
        $qtype = strtolower($qtype[0]);
        
        switch($qtype){
            
            case 'select':
                return call_user_func_array(array($this, 'get'), func_get_args());
            
            case 'update':
                return call_user_func_array(array($this, 'update'), func_get_args());
                
            case 'insert':
                return call_user_func_array(array($this, 'insert'), func_get_args());
                
            case 'delete':
                return call_user_func_array(array($this, 'delete'), func_get_args());
                
            default:
                return null;
        }
        
    }
    
    protected function time_start(){
        
        $this->time = microtime(true);
        
    }
    
    protected function time_stop(){
        
        $time_start = $this->time;
        
        $time_end = microtime(true);
        
        $t = number_format($time_end*1000 - $time_start*1000,3);
        
        return $t;
    }
}




class __mypdo extends ___db{
    
    public $time;
    
    public $entire_sql='';
    
    public function __construct( $dsn, $db_user,$db_password){
    
        parent::__construct( $dsn, $db_user, $db_password);
        
        parent::exec("set names utf8");
        
        parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    }
    
    // 只抓 stmt 
    public function select($arguments,$fetch='FETCH_ASSOC'){
        
        $this->time_start();
        
        if( !is_array($arguments) ){
            $arguments = func_get_args();
            $fetch = PDO::FETCH_ASSOC;
        }else{
            $fetch = $this->fetch_style($fetch);
        }
        
        $stmt = $this->get_stmt( $arguments );
        
        $this->time_stop();
        return $stmt;
    }

    public function get($arguments,$fetch='FETCH_ASSOC'){
        
        $this->time_start();
        
        if( !is_array($arguments) ){
            $arguments = func_get_args();
            $fetch = PDO::FETCH_ASSOC;
        }else{
            $fetch = $this->fetch_style($fetch);
        }
        
        $stmt = $this->get_stmt( $arguments );
        
        $data = array();
        while($row = $stmt->fetch($fetch)){
            $data[]=$row;
        }
        
        $this->time_stop();
        return $data;
    }
    
    
    public function getOne($arguments, $fetch='FETCH_ASSOC'){
        
        $this->time_start();
        
        if( !is_array($arguments) ){
            $arguments = func_get_args();
            $fetch = PDO::FETCH_ASSOC;
        }else{
            $fetch = $this->fetch_style($fetch);
        }
        
        $stmt = $this->get_stmt( $arguments );
        
        $data = $stmt->fetch($fetch);
        /*
        $data = array();
        if($row = $stmt->fetch($fetch)){
            $data=$row;
        }
        */
        $this->time_stop();
        
        return $data;
        
    }
    
    public function update($arguments){
        
        $this->time_start();
        
        if( !is_array($arguments) ){
            $arguments = func_get_args();
        }
        
        $stmt = $this->get_stmt( $arguments );
        
        $this->time_stop();
        
        return $stmt->rowCount();
    }
    
    public function insert($arguments){
        
        $this->time_start();
        
        if( !is_array($arguments) ){
            $arguments = func_get_args();
        }
        
        $stmt = $this->get_stmt( $arguments );
        
        $this->time_stop();
        
        return $this->lastInsertId(); 
    }
    
    public function delete($arguments){
        
        $this->time_start();
        
        if( !is_array($arguments) ){
            $arguments = func_get_args();
        }
        
        $stmt = $this->get_stmt( $arguments );
        
        $this->time_stop();
        
        return $stmt->rowCount();
    }
    
    private function fetch_style($fetch){
        
        switch($fetch){
            
            case 'FETCH_ASSOC':
                return PDO::FETCH_ASSOC;
                break;
            
            case 'FETCH_NUM':
            case 'FETCH_ROW':
                return PDO::FETCH_NUM;
                break;
                
            case 'FETCH_BOTH':
                return PDO::FETCH_BOTH;
                break;
            
            case 'FETCH_BOUND':
                return PDO::FETCH_BOUND;
                break;
            
            case 'FETCH_CLASS':
                return PDO::FETCH_CLASS;
                break;
            
            case 'FETCH_INTO':
                return PDO::FETCH_INTO;
                break;
            
            case 'FETCH_LAZY':
                return PDO::FETCH_LAZY;
                break;
            
            case 'FETCH_NAMED':
                return PDO::FETCH_NAMED;
                break;
            
            case 'FETCH_OBJ':
                return PDO::FETCH_OBJ;
                break;
            
            default:
                break;
        }
        return PDO::FETCH_ASSOC;
    }
    
    private function get_stmt($arguments){
        
        $sql = array_shift($arguments);
        
        $stmt = $this->make_stmt( $sql, $arguments );
        
        return $stmt;
    }
    
    private function make_stmt( $sql,$arguments){
        $this->entire_sql = $sql;
        
        preg_match_all('/:([a-zA-Z][0-9a-zA-Z_\-]*)/i',$sql,$match);
        
        if($match && isset($match[1])) $match = $match[1];
        else $match = array();
        
        $stmt = $this->prepare($sql);

        if($match){
            
            for($i=0,$imax=count($match); $i<$imax; $i++){
                $m = $match[$i];
                
                $pattern = ':'.$m;
                $value = $arguments[$i];
                
                $pdo_param = $this->_determineType($value);
                
                $stmt->bindParam( $pattern, $arguments[$i], $pdo_param);
                $this->entire_sql = str_replace($pattern, $arguments[$i], $this->entire_sql);
            }

            //if(isset($_COOKIE['debug']) && $_COOKIE['debug'] === 'frank') echo "<!--".$this->entire_sql."-->\n";
            
        }
        
        $stmt->execute();
        
        return $stmt;
    }
    
    private function _determineType($item)
    {
        switch (gettype($item)) {
            
            case 'NULL':
                return PDO::PARAM_NULL;
                
            case 'string':
                return PDO::PARAM_STR;

            case 'integer':
                return PDO::PARAM_INT;

            case 'blob':
                return PDO::PARAM_LOB;

            case 'double':
                return PDO::PARAM_STR;
                break;
        }
        return PDO::PARAM_STR;
    }
    
}

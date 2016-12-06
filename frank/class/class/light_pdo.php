<?php 

/**
    $rows = $conn->get($sql);

    $row = $conn->getOne($sql);


*/

    error_reporting(E_ALL);
class light_pdo extends PDO
{

    public $time;
    
    public $entire_sql='';

    
    public function __construct( $dsn, $db_user,$db_password, $fetch='FETCH_OBJ'){
        

        parent::__construct( $dsn, $db_user, $db_password);

        parent::exec("set names utf8");
        
        parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $this->fetch=$fetch; // FETCH_ASSOC FETCH_OBJ FETCH_NUM

    }



    // 只抓 stmt 
    public function select($arguments){
        try{
            $this->time_start();
            
            if( !is_array($arguments) ){
                $arguments = func_get_args();
            }

            $r = $this->parse($arguments);

            $stmt = $this->get_stmt( $r->sql, $r->arguments );
            
            $this->time_stop();
            return $stmt;
        }catch(PDOException $e){
            $this->handle_error($e);
            return false;
        }
    }



    // 快速用 id 讀資料 
    public function qs($tablename, $id, $fields='*'){
        try{
            $sql="select ".$fields." from ".$tablename." WHERE id=:id";
            $row = $this->getOne($sql, $id);
            return $row;

        }catch(PDOException $e){
            $this->handle_error($e);
            return false;
        }
    }





    public function get($arguments){
        try{
            $this->time_start();
            
            if( !is_array($arguments) ){
                $arguments = func_get_args();
            }
            
            $r = $this->parse($arguments);

            $stmt = $this->get_stmt( $r->sql, $r->arguments );
            
            $data = array();
            while($row = $stmt->fetch( $r->fetch ) ){
                $data[]=$row;
            }
            
            $this->time_stop();
            return $data;

        }catch(PDOException $e){
            $this->handle_error($e);
            return false;
        }

    }




    
    
    public function getOne($arguments){
        try{
            $this->time_start();
            
            if( !is_array($arguments) ){
                $arguments = func_get_args();
            }

            $r = $this->parse($arguments);
            
            $stmt = $this->get_stmt( $r->sql, $r->arguments );
            
            $data = $stmt->fetch( $r->fetch );

            $this->time_stop();

            return $data;
        }catch(PDOException $e){
            $this->handle_error($e);
            return false;
        }
    }
    



    public function single($arguments){
        try{
            $this->time_start();
            
            if( !is_array($arguments) ){
                $arguments = func_get_args();
            }

            $r = $this->parse($arguments);
            
            $stmt = $this->get_stmt( $r->sql, $r->arguments );
            
            $data = $stmt->fetch( $r->fetch );

            $this->time_stop();

            if($data===false) return $data;
            foreach($data as $prop) {

                return $prop;
            }
        }catch(PDOException $e){
            $this->handle_error($e);
            return false;
        }
    }




    public function update($arguments){
        try{
            $this->time_start();
            
            if( !is_array($arguments) ){
                $arguments = func_get_args();
            }

            $r = $this->parse($arguments);
            
            $stmt = $this->get_stmt( $r->sql, $r->arguments );
            
            $this->time_stop();
            
            return $stmt->rowCount();

        }catch(PDOException $e){
            $this->handle_error($e);
            return false;
        }
    }



    
    public function insert($arguments){
        try{
            $this->time_start();
            
            if( !is_array($arguments) ){
                $arguments = func_get_args();
            }

            $r = $this->parse($arguments);
            
            $stmt = $this->get_stmt( $r->sql, $r->arguments );
            
            $this->time_stop();
            
            return $this->lastInsertId();
        }catch(PDOException $e){
            $this->handle_error($e);
            return false;
        }
    }
    


    public function delete($arguments){
        
        try{
            $this->time_start();
            
            if( !is_array($arguments) ){
                $arguments = func_get_args();
            }
            
            $r = $this->parse($arguments);
            
            $stmt = $this->get_stmt( $r->sql, $r->arguments );
            
            $this->time_stop();
            
            return $stmt->rowCount();
        }catch(PDOException $e){
            $this->handle_error($e);
            return false;
        }
    }



    private function time_start(){
        
        $this->time = microtime(true);
        
    }
    




    private function time_stop(){
        

        if(isset($this->callback) && is_callable($this->callback)){

            $time_start = $this->time;
            
            $time_end = microtime(true);
            
            $t = number_format($time_end*1000 - $time_start*1000,3);
            $t=$t.'ms';


            call_user_func_array($this->callback, [$t, $this->entire_sql]);

        }
    }


    private function handle_error($e){
        print_r($e->getMessage());
        if(isset($this->error_log) && is_callable($this->error_log)){
            call_user_func_array($this->error_log, [$e]);            
        }
    }

    
    private function get_stmt($sql, $arguments){
        
        
        $stmt = $this->make_stmt( $sql, $arguments );
        
        return $stmt;
    }




    
    private function make_stmt( $sql,$arguments){
        // try{

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

                    $type = gettype($value);
                    $pdo_param = $this->_determineType($type);
                    $stmt->bindParam( $pattern, $arguments[$i], $pdo_param);


                    $replace_str = $arguments[$i];

                    if($type === 'string' || $type === 'blob'){
                        $replace_str = "'".$replace_str."'";
                    }

                    $this->entire_sql = str_replace($pattern, $replace_str, $this->entire_sql);
                }

            }
            
            $stmt->execute();
        

        // }catch(PDOException $e){
            //echo $e->getMessage();

        // }

        return $stmt;
    }




    
    private function _determineType($type)
    {

        if($type === 'NULL') return PDO::PARAM_NULL;

        if($type === 'string') return PDO::PARAM_STR;

        if($type === 'integer') return PDO::PARAM_INT;

        if($type === 'blob') return PDO::PARAM_LOB;

        if($type === 'double') return PDO::PARAM_STR;


        return PDO::PARAM_STR;

    }
    





    private function parse($arguments){
        $r = new stdClass();

        $r->sql='';
        $r->arguments=[];
        $r->fetch=$this->fetch_style($this->fetch);

        $sql = array_shift($arguments);

        if(strpos($sql, 'FETCH_') !== false){
            $r->fetch = $sql;
            $sql=array_shift($arguments);
        }

        $r->sql = $sql;

        $r->arguments = $arguments;


        return $r;
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

}
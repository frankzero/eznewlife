<?php 

/**
$p = lorm('articles');

$p->load_by_id(3);
//echo $p->title;

unset($p->id);

//writeln($p);

$q = lorm('articles');
$q->title = $p->title;
$q->content=$p->content;
$q->category_id=$p->category_id;
$q->photo=$p->photo;
$q->summary=$p->summary;
$q->publish_at=$p->publish_at;
$id = $q->insert();
var_dump($id);


$p = lorm('articles');
$p->id=21413;
var_dump($p->delete() );
*/

class __orm extends __model{ 


    private $p;


    public $primaryKey='id';


    
    function __construct($table, $conn){
        $this->p=new stdClass;
        $this->p->table=$table;
        $this->p->conn=$conn;
    }



    public function load_by_id($id){
        
        $conn = $this->p->conn;
        $table = $this->p->table;

        $sql="select * from $table WHERE id=:id";

        $row = $conn->getOne($sql, $id);


        if($row === false) return ;

        foreach($row as $field => $value){
            $this->_data[$field] = $value;
        }

    }


    function update(){
        
        $pk = $this->primaryKey;

        if( !isset($this->_data[$pk]) ) return false; 

        if(count($this->_data) === 0){
            return false;
        }

        $conn = $this->p->conn;
        $table = $this->p->table;

        $param=[];

        $f=[];

        $sql="update $table SET ";


        foreach($this->_data as $field => $value){
            if($field === $pk) continue;
            $f[]=$field.'=:'.$field;
            $param[]=$value;
        }

        $sql.= implode(',', $f);
        $sql.=" WHERE $pk=:$pk";

        array_unshift($param, $sql);

        array_push($param, $this->_data[$pk]);


        $affect_row_count = $conn->update($param);

        return $affect_row_count;

    }




    function insert(){
        $pk = $this->primaryKey;

        if(count($this->_data) === 0){
            return false;
        }

        $conn = $this->p->conn;
        $table = $this->p->table;

        $sql="INSERT INTO `$table` ({fields}) VALUES ({values})";

        $param=[];
        $fields=[];
        $values=[];


        foreach($this->_data as $field => $value){
            //if($field === $pk) continue;
            $fields[]="`$field`";
            $values[]=":$field";
            $param[]=$value;

        }

        $sql = str_replace('{fields}', implode(',', $fields), $sql);
        $sql = str_replace('{values}', implode(',', $values), $sql);

        array_unshift($param, $sql);

        $last_insert_id = $conn->insert($param);
        
        return $last_insert_id;
    }


    function delete(){
        $pk = $this->primaryKey;

        if( !isset($this->_data[$pk]) ) return false; 

        $conn = $this->p->conn;
        $table = $this->p->table;

        $sql="DELETE from `$table` WHERE $pk=:$pk";

        $v = $this->_data[$pk];

        return $conn->delete($sql, $v);
    }

}
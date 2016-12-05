<?php 
/*
    dir:asc, desc 
    category_id : all 1 2 3 4 5
    status 0 1 2 all
    flag : G P 
    length : 10 25 50 100
    page

*/
class articleCollection{

    private $conn;

    private $where=[];

    private $arguments=[];

    public $_data;


    public function __construct(){
        $this->conn = oconn('M');
        $this->page=1;
        $this->length=10;
        $this->dir='DESC';

    }



    public function __get($prop){
        return $this->_data[$prop];
    }



    public function __set($prop, $value){

        if($value==='all') return;
        if($value==='') return;

        $this->_data[$prop] = $value;
    }


    public function load_by_ids($rows){

       
        $data=[];

        if($rows===false) return $data;

        for ($i=0,$imax=count($rows); $i < $imax; $i++) { 
            $row=$rows[$i];
            $article = new __article($this->conn);
            $article->load_by_id($row->id);
            $data[]=$article->_data;
        }


        return $data;
    }


    public function query(){

        $sql="select id from articles ";

        $sql.=$this->makeWhere();

        $sql.= $this->make_order();
        $sql.= $this->make_limit();
        

        $rows = $this->queryData($sql);

        //echo $this->conn->entire_sql;print_r($arguments);exit;

        return $this->load_by_ids($rows);
    }



    public function pageBar($size=10){
        $maxPage = $this->getMaxPage($size);

        $pagebar = new __pagebar($maxPage, $this->page, $size);
        return $pagebar->bar();
    }




    private function getMaxPage($size){

        $sql="select count(id) as c from articles ";
        $sql.=$this->makeWhere();

        array_unshift($this->arguments, $sql);

        $row = $this->conn->getOne($this->arguments);

        $c = $row->c;

        

        $maxPage = ceil($c/$size);

        return $maxPage;

    }


    private function queryData($sql){
        array_unshift($this->arguments, $sql);

        $rows = $this->conn->get($this->arguments);

        return $rows;
    }


    private function makeWhere(){
        
        $this->where=[];
        $this->arguments=[];

        foreach($this->_data as $prop => $value){
            $function_name = 'where_'.$prop;
            if(method_exists($this, $function_name)){
                call_user_func([$this, $function_name]);
            }
        }

        if(count($this->where) !== 0){
            return 'where '.implode(' and ', $this->where);
        }

        return '';
    }



    private function where_category_id(){
        if( isset($this->_data['category_id']) ){
            $this->where[]="category_id=:category_id";
            $this->arguments[]=$this->_data['category_id'];
        }
    }



    private function where_status(){
        if( isset($this->_data['status']) ){
            $this->where[]="status=:status";
            $this->arguments[]=$this->_data['status'];
        }
    }



    private function where_is_deleted(){

        if( isset($this->_data['is_deleted']) ){
            
            if($this->_data['is_deleted'] === '0'){
                $this->where[]="deleted_at is null";
            }
            

            if($this->_data['is_deleted'] === '1'){
                $this->where[]="deleted_at is not null";
            }
        }
    }



    private function where_flag(){
        if( isset($this->_data['flag']) ){
            $this->where[]="flag=:flag";
            $this->arguments[]=$this->_data['flag'];
        }
    }



    private function where_search(){
        if( isset($this->_data['search']) ){
            $search=$this->_data['search'];
            $w=[];
            
            if(isset($this->_data['search_title'])){
                $w[] = "title LIKE :title";
                $this->arguments[]='%'.$search.'%';
            }


            if(isset($this->_data['search_content'])){
                $w[] = "content LIKE :content";
                $this->arguments[]='%'.$search.'%';
             }


            if(isset($this->_data['search_tag'])){

                $tag_name = $search;
                $sql="select taggable_id from tagging_tagged WHERE tag_name LIKE '%".$tag_name."%'";

                $rows = $this->conn->get($sql, $tag_name);
                $ids=[];
                for ($i=0,$imax=count($rows); $i < $imax; $i++) { 
                    $row=$rows[$i];
                    $ids[]=$row->taggable_id;
                }
                

                if(count($ids) !== 0){
                    $w[] = "id IN(".implode(',', $ids).")";
                }

            }


            if(isset($this->_data['search_id'])){
                

                if(is_numeric($search)){
                    $w[] = "id=:id";
                    $this->arguments[]=$search;
                }
                

                $unique_id=$search;
                $sql="select articles_id from articles_map WHERE unique_id=:unique_id";
                $row = $this->conn->getOne($sql, $unique_id);

                if($row !== false){
                    $w[] = "id=:articles_id";
                    $this->arguments[]=$row->articles_id;
                }
            }


            if(count($w) !== 0){
                $this->where[] = '('.implode(' OR ', $w).')';
            }
        }

    }




    private function where_instant(){
        if( isset($this->_data['instant']) ){
            $this->where[]="instant=:instant";
            $this->arguments[]=$this->_data['instant'];
        }

    }



    private function where_created_user(){
        if( isset($this->_data['created_user']) ){
            $this->where[]="created_user=:created_user";
            $this->arguments[]=$this->_data['created_user'];
        }

    }


    private function make_order(){
        return ' ORDER BY id '.$this->dir;
    }



    private function make_limit(){
        $start = ($this->page-1) * $this->length;
        $end = $this->length;

        return " LIMIT {$start},{$end}";
    }

}
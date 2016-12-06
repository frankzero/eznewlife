<?php 

class __router{

    public function __construct(){
        $this->init();
    }

    public function init(){
        $this->matched = false;
        $this->query_string = $this->get_query_string();
        $this->method = $_SERVER['REQUEST_METHOD'];

    }


    public function execute(){
        $app=light::app();

        $host = $app->host;

        $file = $app->path->app.'route.php';

        if(file_exists($file)){
            $this->load($file);
            return;
        }

        $file = __APP__.'__default/route.php';

        if(file_exists($file)){
            $this->load($file);
            return;
        }        
        

    }




    public function load($file){

        require $file;
    }



    public function get_query_string(){
        $query_string = $_SERVER['REQUEST_URI'];
        if( strpos($query_string, '?') !== false){
            $query_string = explode('?',$query_string);
            $query_string = $query_string[0];
        }
        return $query_string;
    }


    public function get( $pattern, $caller){
        
        if($this->method !== 'GET') return false;

        if($this->matched === true) return false;

        return $this->exec($pattern, $caller);

    }


    public function post($pattern, $caller){

        if($this->method !== 'POST') return false;

        if($this->matched === true) return false;

        return $this->exec($pattern, $caller);
    }


    public function put($pattern, $caller){

        if($this->method !== 'PUT') return false;

        if($this->matched === true) return false;

        return $this->exec($pattern, $caller);
    }


    public function delete($pattern, $caller){

        if($this->method !== 'DELETE') return false;

        if($this->matched === true) return false;

        return $this->exec($pattern, $caller);
    }


    public function exec($pattern, $caller){

        $r = $this->handle( $pattern );

        if( $r !== false ) {
            $this->matched = true;
            call_user_func_array($caller , $r);
            return true;
        }

        return false;
    }

    public function handle( $patterns ){
        
        if( !is_array($patterns)){
            $patterns = [$patterns];
        }


        for( $i=0,$imax=count($patterns); $i<$imax; $i++){
            
            $pattern = $patterns[$i];
            
            $r = $this->match($pattern);

            if($r !== false) return $r;

        }

        return false;

    }


    public function match($pattern){
        
        if( $pattern === '*') return [];

        $pattern = $this->addslashes($pattern);
        
        $bool = preg_match( '/^'.$pattern.'$/', $this->query_string, $matches);
        
       
        if($bool){
            array_shift($matches);
            return $matches;
        }
        return false;
    }

     public function addslashes($pattern){
        $pattern = str_replace('{int}','([0-9]+)',$pattern);
        $pattern = str_replace('{string}','([^/]+)',$pattern);
        $pattern = str_replace('{char}','([a-zA-Z]+)',$pattern);
        $pattern = str_replace('{mix}','([a-zA-Z0-9_\-]+)',$pattern);
        $pattern = str_replace('{any}','(.+)',$pattern);
        $pattern = str_replace('/','\/',$pattern);
        return $pattern;
        
        //$pattern = addslashes($pattern);
        $pattern = str_replace('/','\/',$pattern);
        return $pattern;
    }

}
<?php 

class __model{

    
    public  $_data=[];


    public function __construct(){

        
    }




    public function __get($key){
        if(isset($this->_data[$key])){
            return $this->_data[$key];
        }

        return null;
    }




    public function __set($key, $value){
        $this->_data[$key] = $value;
    }




    public function __isset($key){
        return isset($this->_data[$key]);
    }



    public function __unset($key){
        unset($this->_data[$key]);
    }



}
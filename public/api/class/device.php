<?php 

namespace ff\core\module\http_request;


class device{

    private $_data=[];

    private $user_agent=null;

    function __construct($user_agent=null){
        $this->user_agent=$user_agent;
    }


    function __get($prop){
        if(isset($this->_data[$prop])) return $this->_data[$prop];

        $method = 'make_'.$prop;

        if(method_exists($this, $method)){
            $this->_data[$prop] = call_user_func([$this, $method]);
            return $this->_data[$prop];
        }


        return null;
    }



    function __set($prop, $value){

    }


    function __debugInfo(){
        
        $fs=['name', 'code', 'is_mobile', 'is_tablet', 'is_phone', 'is_pc'];

        $data=[];

        for ($i=0,$imax=count($fs); $i < $imax; $i++) { 
            $f=$fs[$i];
            $data[$f] = $this->$f;
        }

        return $data;

    }


    private function make_Mobile_Detect(){

        if($this->user_agent===null) return new Mobile_Detect();

        return new Mobile_Detect(null, $this->user_agent);
    }



    private function make_name(){

        if($this->Mobile_Detect->isTablet() ) return 'tablet';

        if($this->Mobile_Detect->isMobile() ) return 'mobile';

        return 'pc';

    }


    private function make_code(){
        if($this->Mobile_Detect->isTablet() ) return 2;

        if($this->Mobile_Detect->isMobile() ) return 1;

        return 0;
    }


    private function make_is_mobile(){

        if($this->code === 0) return 0;

        return 1;
    }



    private function make_is_phone(){

        if($this->code === 1) return 1;

        return 0;
    }



    private function make_is_tablet(){
        if($this->code === 2) return 1;

        return 0;
    }


    private function make_is_pc(){
        if($this->code === 0) return 1;

        return 0;   
    }
}

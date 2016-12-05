<?php 

class __adcode{

    public  $_data=[];
    public $id;
    public $plan;
    public $domain;

    public function __construct($id, $plan, $domain){
        $this->id = $id;
        $this->plan = $plan;
        $this->domain = $domain;

        $this->getCode();
        $this->addTitle();
        // $this->check_15();
        $this->checkDebug();
    }


    public function __get($key){

        if(isset($this->_data[$key])){
            return $this->_data[$key];
        }

        return '';
    }




    public function __set($key, $value){
        $this->_data[$key] = $value;
    }





    private function getCode(){
        
        $ad_block_id = $this->id;
        $plan = $this->plan;
        $domain = $this->domain;

        $conn = ff\conn('S');

        $r = new stdClass();
        $r->id=$ad_block_id;

        $sql="select code,code_onload,rate from adv_code where plan=:plan and adv_id=:ad_block_id and domain=:domain";
        $row=$conn->getOne($sql, $plan, $ad_block_id, $domain);
      // echo $sql;

        $code=$row['code'];
        $code_onload=$row['code_onload'];
        $rate=$row['rate']-0;


        if(rand(1, 100) < $rate ){
            $this->code = $code_onload;
        }else{
            $this->code = $code;
        }


        
    }



    private function addTitle(){

        if($this->code === '') return;

        if(strpos($this->code, 'cutey5372') !== false) return;
        if(strpos(__DOMAIN__, 'dark') !== false) return;
        if(strpos(__DOMAIN__, 'avbody') !== false) return;
        
        if($this->id===15) return;

        $this->code='<div style="text-align:center;">廣告</div>'.$this->code;

    }


    private function checkDebug(){
        if(isset($_GET['ad'])){
            $this->code =  '<div style="background-color: #ffba00;"> <div>廣告'.$this->id.'</div> '.$this->code.' </div>';
        }
    }


    private function check_15(){
        
        // 跳上跳下就不打開 15號 

        if($this->code === '') return;
        if($domain !== 'eznewlife.com') return;
        if($this->id !== 15) return;


        if( $this->runffb() ){
            $this->code='';
            return;
        }


        $cookie_name = 'ad_block_15';

        if(isset($_COOKIE[$cookie_name]) ){
            $this->code = '';
            return;
        }else{
            
            $hour = fconfig('ad_window_time');
            $this->setCookie($cookie_name, '1', $hour);

            return;            
        }

    }



    private function runffb(){
        //ffb, rffb, ffb_time, ad_window_time
        if( !isset($_COOKIE['ffb_time']) ){

        }


    }


    private function setCookie($prop, $value, $hour){
        $hour = time() + $hour * 60 * 60;
        $hour = $hour*1000;

        $code='';

        $code.="\n<script>";
        //$code.='var expires = new Date('.($hour).').toGMTString();';
        $code.="document.cookie='$prop=$value;Path=/;expires='+new Date($hour).toGMTString();";
        $code.='</script>'."\n";

        $this->code.=$code;
    }



    private function getCookie($prop){

        if(isset($_COOKIE[$prop]) ) {
            return $_COOKIE[$prop];
        }

        return '';
    }
}
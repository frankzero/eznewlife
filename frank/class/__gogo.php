<?php 
/*
flow
http://59.126.180.51/demo/flow/?type=enl

*/
class __gogo{

    public $code='';

    public $rate;

    private $_data=[];


    public function __construct($plan, $domain){
        $this->ffb=$this->fromFB();
        $this->rffb=fconfig('rffb')-0;
        $this->ffb_time=fconfig('ffb_time')-0;
        $this->ad_window_time=fconfig('ad_window_time')-0;
        $this->delayffb = fconfig('delayffb')-0;
        $this->delaybuttonshow = fconfig('delaybuttonshow')-0;
        $this->ffrp=fconfig('ffrp');
        $this->code='';
        $this->plan = $plan;
        $this->domain = $domain;

        $this->page = $this->page();

        $this->init();
    }



    public function code(){

        if($this->code==='') return '';

        return $this->code;

        $code='';
        $code.="\n <script>";
        $code.="setTimeout(function(){".$this->code."}, ".$this->delayffb.");";
        $code.="</script> \n";

        return $code;
    }




    private function page(){
        if(isset($_GET['page'])) return $_GET['page']-0;

        return 1;
    }



    private function init(){

        $this->load($this->ffrp);

        if($this->prepare_jump()){

            return;
        }

        if($this->prepare_window()){
            return;
        }

        $this->log('nnn');
    }


    // 75,100|40,-350|0,-50
    private function load($str){
        $str = trim($str);

        $ss = explode('|', $str);

        for ($i=0,$imax=count($ss); $i < $imax; $i++) { 
            $s=$ss[$i];
            
            if(strpos($str, ',') === false) continue;

            $g = explode(',', $s);

            $this->set($g[0]-0, $g[1]-0);
        }


        $s=0;
        $e=0;

        $this->rate=[];

        for ($i=0,$imax=count($this->_data); $i < $imax; $i++) { 
            $d=$this->_data[$i];
            $s=$e;
            $e = $s + $d->rate;

            $p=new stdClass;
            $p->min=$s;
            $p->max = $e;
            $p->data = $d;

            $this->rate[] = $p;
        }

    }



    private function set($rate, $pixel, $name=''){

        $p = new stdClass;
        $p->rate = $rate;
        $p->pixel = $pixel;
        $p->name=$name;
        $this->_data[] = $p;
    }


    
    private function prepare_jump(){

        $this->log('ffb ?');
        if(!$this->ffb){
            $this->log('N');
            return false;
        }
        $this->log('Y');

        $this->log('page=1 ?');
        if($this->page !== 1){
            $this->log('N');
            return false;
        }
        $this->log('Y');

        $_rand = rand(1, 100);

        $this->log('rffb  dice pass ?', $_rand, '<', $this->rffb);

        if( $_rand > $this->rffb){
            $this->log('N');
            return false;
        }

        $this->log('Y');

        if(!$this->is_jump_timeup()){
            return false;
        }




        $rate=rand(1, 100);
        for ($i=0,$imax=count($this->rate); $i < $imax; $i++) { 
            $d=$this->rate[$i];
            $min = $d->min;
            $max = $d->max;
            $pixel = $d->data->pixel;

            $this->log(' === ', $i, $min, '<', $rate, '<=',$max, $pixel);

            if($rate > $min && $rate <= $max){
                $this->log('move '.$pixel);
                $this->moveScroll($pixel);
                return true;
            }
        }
        
        
        $this->log('m n');

        return false;
    }


    private function log(){


        if(!isset($_GET['log'])  && !isset($_COOKIE['debug'])) return;

        $args = func_get_args();

        $log = [];

        for ($i=0,$imax=count($args); $i < $imax; $i++) { 
            $a=$args[$i];
            

            if(!is_numeric($a)) $a = "'".$a."'";

            $log[]=$a;
        }


        $code="\n <script>";

        $code.="console.log(".implode(',', $log).")";

        $code.="</script> \n";

        $this->code.=$code;
    }

    

    private function moveScroll($pixel){

        //$delay = $this->delaybuttonshow + $this->delayffb ;
        $delay = $this->delayffb;

        $code="\n <script>";
        $code.="function jjj(){";
        $code.="console.log('jjj');";

        $code.='setTimeout(function() {';
            $code.="window.scrollTo(0,document.body.scrollTop + $pixel);";
        $code.="}, $delay );";

        $code.="}";

        //$code.="window.scrollTo(0,document.body.scrollTop + $pixel);";
        
        $code.="</script> \n";

        $this->code.=$code;
    }


    
    private function prepare_window(){
        

        if(!$this->is_window_timeup()) return false;

        $code = get_adCode(15, $this->plan, $this->domain);

        $this->code.=$code;

        return true;

    }


    private function is_window_timeup(){
        $this->log('window timeup?');

        $this->log('ad_window_time = '.$this->ad_window_time);
        if($this->ad_window_time === 0){
            $this->log('Y 1');
            return true;
        }
        
        if($this->ad_window_time === -1) {
            $this->log('N 1');    
            return false;
        } 

        // open window time
        $owt = $this->getCookie('owt');


        if($owt === ''){
            $this->setCookie('owt', '1', $this->ad_window_time);
            $this->log('Y 2');
            return true;
        }

        $this->log('N 1');
        return false;

    }



    private function is_jump_timeup(){
        
        $this->log('jump timeup?');

        if($this->ffb_time === 0) {
            $this->log('Y 1');
            return true;
        }

        $jtu = $this->getCookie('jtu');


        if($jtu === ''){
            $this->setCookie('jtu', '1', $this->ffb_time);
            $this->log('Y 2');
            return true;
        }

        $this->log('N 1');
        return false;
    }


    private function setCookie($prop, $value, $hour){

        if(isset($_GET['log'])){
            // 測試用 $hour =  秒數 x 3
            $hour = time()+$hour*5;
            $hour= $hour*1000;
        }else{
            $hour = time() + $hour * 60 * 60;
            $hour = $hour*1000;
        }
        

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




    private function fromFB(){


        if(isset($_GET['ffb'])) return 1;

        

        if(!isset($_SERVER['HTTP_REFERER'])) return 0;

        

        if( strpos($_SERVER['HTTP_REFERER'], 'facebook')!==false ){
            return 1;
        }

        
        $this->log('from getez ?');

        if($this->fromGetez()){
            $this->log('Y');
            return 1;
        }

        $this->log('N');
        return 0;
    }



    // dark  從 getez過來的 就上下跳 
    private function fromGetez(){
        $host = http::host();
        $shost = http::shost();

        $referer = $_SERVER['HTTP_REFERER'];

        if($referer === '') return false;
        

        $referer_host= parse_url($referer, PHP_URL_HOST);


        if($referer_host === $host) return false;

        if( strpos($host, $referer_host) !== false) return true;


        return false;

    }

}
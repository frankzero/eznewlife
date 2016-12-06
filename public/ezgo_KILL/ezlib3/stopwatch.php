<?php 
if(!class_exists('StopWatch')){
    class StopWatch{
        public $time;
        public $starttime;
        
        public function __construct($autostart=true){
            if($autostart) $this->starttime = microtime(true);
        }
        
        public function play(){
            $this->starttime = microtime(true);
        }
        
        public function stop($n=6){
            $end = microtime(true)*1000;
            $this->starttime = $this->starttime*1000;
            $this->time = number_format($end - $this->starttime,$n);
            //$this->time = number_format($this->time,$n);
            return $this->time;
        }
    }
}
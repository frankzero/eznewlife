<?php 


class device{
    
    public function __construct(){
        $this->Mobile_Detect = new Mobile_Detect();
        $this->device_code = $this->device_code();
        $this->device = $this->device();
    }

    public function is_mobile(){
        if($this->device_code === 0) return 0;

        return 1;
    }

    public function device_code(){

        if($this->Mobile_Detect->isMobile()) return 1;
        if($this->Mobile_Detect->isTable()) return 2;
        return 0;
    }


     public function is_phone(){

        if($this->device_code === 1) return 1;
        return 0;
    }
    
    public function is_tablet(){
        if($this->device_code === 2) return 1;
        return 0;
    }
    
    public function is_pc(){
        if($this->device_code === 0) return 1;
        return 0;
    }

    public function device(){

        if($this->Mobile_Detect->isTablet() ) return 'tablet';

        if($this->Mobile_Detect->isMobile() ) return 'mobile';

        return 'pc';
    }

}
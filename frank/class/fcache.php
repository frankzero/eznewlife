<?php 
/****
走 cache ?
    Y -> cache file exists ? 
        Y -> cache -> exit
        N -> go on 
    N -> go on 



mkdir /home/eznewlife/ad.eznewlife.com/laravel/storage/fcache/
chmod 777 /home/eznewlife/ad.eznewlife.com/laravel/storage/fcache/
chown apache.apache /home/eznewlife/ad.eznewlife.com/laravel/storage/fcache/

*******/

class fcache{


    public $livetime=60;

    public $ddtext='';

    public function __construct(){

        $this->url = $this->current_page_url();

        $this->device = new device();

        $this->livetime = $this->livetime();

        $this->filename = $this->filename();

        $this->static_file = __APP__.'storage/fcache/'.$this->filename;
        $this->re_static_file = __APP__.'storage/fcache/'.$this->filename.'.re';

        // if(!file_exists($this->re_static_file)) file_put_contents($this->re_static_file, $this->url);

        $this->savefile=false;

        $this->enable = $this->enable();

        $this->content='';

        $this->ts = microtime(true);


    }



    public static function make(){
        static $self = null;

        if($self !== null) return $self;

        $self = new self();
        return $self;
    }

    
    public function filename(){
        $f = $this->url;
        $f = $f.$this->device->is_mobile();
        $f = md5($f).'.html';
        return $f;
    }


    public function filename_bk(){

        preg_match('/\/[0-9]+/', $this->url, $match);

        $prefix='0';

        if($match){
            $prefix=str_replace('/', '', $match[0]);
        }

       
        $f = $this->url;
        $f = $f.$this->device->is_mobile();
        //$f = hash('sha512', $f).'.'.$prefix.'.html';
        $f = md5($f).'.'.$prefix.'.html';

        return $f;
    }



    public function enable(){

        

        if(strpos($this->url, 'admin') !== false){
            $this->savefile=false;
            return false;
        }

        
        if( isset($_COOKIE['debug']) && $_COOKIE['debug']==='frank'){
            $this->dd('e8');
            $this->savefile=false;
            return false;
        }


        /*
        if( isset($_COOKIE['XSRF-TOKEN']) ){
            $this->dd('e9');
            $this->savefile=false;
            return false;
        }
        */

        
        $this->dd('e1');
        if( $this->livetime === 0){
            $this->dd('e2');
            $this->savefile=false;
            return false;
        }



        //檔案不存在 
        if(!file_exists($this->static_file)){
            $this->dd('e3');
            // $this->f = new ffile($this->static_file);
            // $this->f->lock();
            $this->savefile=true;
            return false;
        }

        
        // 檔案大小=0
        if(filesize($this->static_file) === 0){
            $this->dd('e4');
            $this->savefile=true;
            return false;
        }



        // 檢查時間 時間還沒到 走cache
        if( (time() - filemtime($this->static_file) ) <= $this->livetime ){
            $this->dd('e5');
            $this->savefile=false;
            return true;
        }


        $this->f = new ffile($this->static_file);


        // 已經被鎖 
        if(!$this->f->lock()){
            $this->dd('e6');
            $this->savefile=false;
            return true;
        }


        $this->dd('e7');
        // 已經被我鎖定
        $this->savefile=true;
        return false;
    }





    public function response(){
        
        
        if(!$this->enable) return false;

        echo file_get_contents($this->static_file);

        $filetime = filemtime($this->static_file);


        $this->dd('cache '.(time() - $filetime));

        $this->costTime();

        //var_dump($this->ddtext);
        exit;
        return true;
        
    }




    public function costTime(){
        $this->dd(''.(microtime(true) - $this->ts ));
    }



    public function start(){
        ob_start();
    }



    public function stop(){
        $html = ob_get_contents();
        ob_end_clean();
        $this->content=$html;
        return $html; 

    }


    public function save($content=null){

        if(!$this->savefile) return $this;

        if($content===null) $content = $this->content;

        if(!$content) return $this;

        // file_put_contents($this->re_static_file, $this->url);
       // file_put_contents($this->static_file, $content);
        file_put_contents($this->static_file, $content.'');

        //$this->log();

        return $this;
    }


    public function log(){

        if(!isset($_GET['log'])) return;
        echo $this->ddtext;
    }


    private function current_page_url(){
        static $current_page_url = null;

        if($current_page_url !== null)  return $current_page_url;

        $current_page_url = 'http';
        if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$current_page_url .= "s";}
        $current_page_url .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $current_page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; // REQUEST_URI ,PHP_SELF
        } else {
            $current_page_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        // $current_page_url = explode('?',$current_page_url);
        // $current_page_url = $current_page_url[0];
        $current_page_url = urldecode($current_page_url);

        return $current_page_url;
    }




    private function livetime(){
    
        return 60;        

        if($this->la() > 6) return 60000;

        //return 20;
        
        /*
        $livetime = 60;


        if(!$livetime) $livetime=0;
        else $livetime = $livetime-0;

        return $livetime;
        */
    }


    private function dd($text){
        $this->ddtext.="\n<!--";
        
        $this->ddtext.=print_r($text, true);

        $this->ddtext.="-->\n";
    }



    private function la(){
        $r = sys_getloadavg();

        return $r[0]-0;
    }
}
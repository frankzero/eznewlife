<?php 

/*
    flock 

    LOCK_SH - Shared lock (reader). Allow other processes to access the file
    LOCK_EX - Exclusive lock (writer). Prevent other processes from accessing the file
    LOCK_UN - Release a shared or exclusive lock
    LOCK_NB - Avoids blocking other processes while locking
*/


class ffile{
    

    private $closed=false;


    public function __construct($file){
        $this->file=$file;

        if(!file_exists($this->file)) $this->fp = fopen($file,"w+");
        else $this->fp = fopen($file,"r+");
    }



    public function __destruct(){
        $this->close();
    }




    public function lock(){
        
        $bool = flock($this->fp,LOCK_EX | LOCK_NB);

        return $bool;

    }




    public function unlock(){
        flock($this->fp,LOCK_UN);
    }




    public function write($content){
        ftruncate($this->fp, 0); // clear the content 
        rewind($this->fp); // pointer back to first line 
        fwrite($this->fp, $content);
    }




    public function read(){

        rewind($this->fp); // pointer back to first line 

        $content = '';
        
        while (  ($buffer = fgets($this->fp, 4096) ) !== false) {
            $content.=$buffer;
        }
        return $content;
    }




    public function append($content){
        fseek($this->fp, 0, SEEK_END); // Set position to end-of-file plus offset.
        fwrite($this->fp, $content);
    }




    public function close(){
        
        if($this->closed === true) return;

        $this->closed=true;

        fclose($this->fp);
    }



    public function delete(){
        unlink($this->file);
    }
}
<?php 

abstract class ab_replacer{
    

    protected $html; 
    protected $url;
    protected $host;


    public function __construct($html, $url){
        $this->url = $url;
        
        $this->html = $html;
       
        $this->dir=UPLOADS;
        $this->host = parse_url($url, PHP_URL_HOST);
        $this->query = file_get_html($html);

        $scheme = parse_url($url,PHP_URL_SCHEME);
        $this->domain = $scheme.'://'.$this->host;
        $this->replace_image();
    }



    public function getHtml(){
        return $this->query.'';
        //$doms = $this->query->find('body');
        //return $doms[0]->innertext;
    }


    abstract protected function handle_image_src(simple_html_dom_node $img);


    abstract protected function make_image_url($path);


    protected function replace_image(){

        $imgs = $this->query->find('img');

        $max=count($imgs);


        for ($i=0,$imax=count($imgs); $i < $imax; $i++) { 
            $img=$imgs[$i];
            setSatus('圖像處理 '.$i.'/'.$max, $i*100/$max);
            $url = $this->handle_image_src($img);

            $imageFileType  = $this->getimageFileType($url);

            $filename=time().'-'.unique_id(5).'.'.$imageFileType;
            // $filename=$this->filename($imageFileType);
            $file = $this->dir.$filename;
            $image_url = '/uploads/'.$filename;

            $image_string = @file_get_contents($url);

            if($image_string === false){
                $img->setAttribute('src','');
                $img->setAttribute('data-mce-src','');
                continue;
            }

            
            file_put_contents($file, $image_string);

            $img->outertext='<img src="'.$image_url.'" data-mce-src="'.$image_url.'" \>';

            //$img->setAttribute('src',$image_url);
            //$img->setAttribute('data-mce-src',$image_url);

        }

    }


    protected function filename($imageFileType){
        static $i=0;

        $i++;

        return 'test-'.$i.'.'.$imageFileType;
    }


    protected function getimageFileType($file){
        $file = str_replace('http://', '', $file);
        $file = str_replace('https://', '', $file);
        $file  = pathinfo($file, PATHINFO_EXTENSION);

        if(strpos($file, '?') !== false){
            $file = explode('?', $file);
            $file = $file[0];
        }


        if($file==='') $file='jpg';

        return $file;
    }

}
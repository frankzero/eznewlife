<?php 
namespace _scraping;

use \simple_html_dom_node;


abstract class replace_image{


    protected $dir;
    protected $html;
    protected $url;
    protected $host;
    protected $query;
    protected $domain;


    function call($html, $url){
       
        $this->dir=UPLOADS;
        $this->html = $html;
        $this->url = $url;
        $this->host = parse_url($url, PHP_URL_HOST);
        $this->query = file_get_html($html);

        $scheme = parse_url($url,PHP_URL_SCHEME);

        $this->domain = $scheme.'://'.$this->host;

        $this->replace_image();

        return $this->query.'';
    }




    protected function replace_image(){


        $imgs = $this->query->find('img');

        $max=count($imgs);


        for ($i=0; $i < $max; $i++) { 
            $img=$imgs[$i];
            $this->setSatus('圖像處理 '.$i.'/'.$max, $i*100/$max);
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




    protected function handle_image_src(simple_html_dom_node $img){

        $url = $img->getAttribute('data-src');

        if(!$url) $url = $img->getAttribute('data-original');
        
        if(!$url) $url = $img->getAttribute('file');

        if(!$url) $url = $img->getAttribute('src');

        $url = $this->make_image_url($url);

        return $url;
    }





    protected function make_image_url($path){
        if(strpos($path, 'http') !== false) return $path;
        if(strpos($path, '/') !== 0) $path = '/'.$path;
        return $this->domain.$path;
    }





    protected function setSatus($text, $p){
        echo '/***/'.$text.','.$p;
        ob_flush();
        flush();
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

        if( !in_array($file, ['jpg', 'png', 'jpeg', 'gif', 'bmp']) ){
            $file='jpg';
        }


        return $file;
    }
}
<?php 

abstract class ab_handle_string{

    public $url;

    public $host;

    public function __construct($html, $url){
        
        $this->html = $html;

        $this->url = $url;

    }


    abstract public function response();

}
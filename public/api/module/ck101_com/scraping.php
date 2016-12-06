<?php 

namespace ck101_com;


use _scraping\scraping as ab;


class scraping extends ab{


    function __construct($url){

        parent::__construct($url, __NAMESPACE__);

    }


}
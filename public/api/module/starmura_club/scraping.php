<?php 

namespace starmura_club;


use _scraping\scraping as ab;


class scraping extends ab{


    function __construct($url){

        parent::__construct($url, __NAMESPACE__);

    }


}
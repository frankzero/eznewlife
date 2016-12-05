<?php

namespace App\Http\Middleware;

use Closure;

class MobileDetect{

    public function __construct(){

    }

    public function isMobile(){
        return c('device')->is_mobile();
    }
}
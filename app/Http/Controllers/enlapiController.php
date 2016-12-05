<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Article, App\Category, App\User;
use Auth, Input;
use Carbon\Carbon;
use DB;
use ff;
use Cache;
use File;
class enlapiController extends Controller
{
    public function api()
    {
        $cmd = Input::get('cmd');

        if($cmd === null){
            return '';
        }

        
        if($cmd === 'checkflag'){

            $conn = \oconn('S');

            $id = Input::get('id');

            $sql="select articles_id from articles_map where unique_id=:unique_id";


            $row = $conn->getOne($sql, $id);

            if($row===false){
                echo '0';
                die();
            }

            $id = $row->articles_id;
            

            $a = new \__article( $conn );

            $a->load_by_id($id);


            if($a->isPorn == 0){
                echo 'G';
                die();
            }

            echo 'P';
            die();
        }


        return '';

    }

}
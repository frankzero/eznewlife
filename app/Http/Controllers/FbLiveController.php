<?php

namespace App\Http\Controllers;

use App\FbLive;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User,App\FbLiveLog;
class FbLiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function  notfound(){
       // $agent = new Agent();
        $page = ['title' => '404', 'sub_title' =>'頁面不存在',
            'url' => '', 'share_url' => url("/"), 'photo' => ''];
        return view('fb_lives.404')->with('plan', 1)
          //  ->with('mobile', $agent->isMobile())
            ->withPage($page);//->with('categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $fb_lives=FbLive::find($id);
        $fb_lives->count=0;;
        $emoji=['like','love','haha','wow','sad','angry'];
        //dd($emoji);
        /*
        $answers=[];
        foreach ($emoji as $k=>$face) {
            $face_txt = $face . "_txt";
            //echo $face_txt;
            //echo  $fb_lives->{$face_txt};
            $tmp = $fb_lives->{$face_txt};
            if (!empty($tmp)) {
                $count++;
                $answers[$face] = $tmp;


            }
        }*/
        $answers=[];
        if ($fb_lives->answers) {
            $answers = explode(",", $fb_lives->answers);
            $fb_lives->count=count($answers);
        }

        switch ( $fb_lives->count){
            case 1:
                $class="col-sm-12";
                break;
            case  2:
                $class="col-sm-6";
                break;
            case  3:
                $class="col-sm-4";
                break;
            case  4:
                $class="col-sm-3";
                break;
            case  5:
            case  6:
                $class="col-sm-2";
                break;
            default :
                $class="col-sm-1";
                break;
        }
        //exit;
      //  dd($answers);
        if ($fb_lives) {
            //$reactions=array_change_key_case($answers, CASE_UPPER);
            $reactions = array_map('strtoupper', $answers);
        //    $reactions = array_map('strtoupper',   ($answers));
         //   dd($reactions);
            $reactions="'".implode("','",array_values( $reactions))."'";
           // $reactions= html_entity_decode( $reactions, ENT_QUOTES);
       //dd($reactions);
            return view('fb_lives.show')->with("fb_lives", $fb_lives)->with("reactions", $reactions)
            ->with("answers", $answers)->with('class',$class);
        }else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

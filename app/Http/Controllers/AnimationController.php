<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Jenssegers\Agent\Agent;
use Carbon\Carbon;
use App\Animation,App\User;
use Auth,Input,Response,Mail;
use yajra\Datatables\Datatables;
use DB;
use Image,Config;
class AnimationController extends Controller
{

    public function rand()
    {
        //dd('動圖 程式維護更新中');


       // $rand_animations = Animation::with('author')->orderBy("id",'desc')->paginate(10);


        $rand_animations= Animation::join('users as u1', 'animations.created_user', '=', 'u1.id')  ;// ['created_by']
        $users=User::lists('name', 'id')->toArray();
if (Input::get('user') and array_key_exists(Input::get('user'),$users))  $rand_animations=  $rand_animations->where('created_user',Input::get('user'));
    $rand_animations=      $rand_animations->select('animations.*','u1.name')->orderBy("animations.id",'desc')->paginate(10);
        //$users=User::lists('name', 'id');
      //  dd($users);
//dd($rand_animations);
        // where('photo','like','.gif')->
        return view('animations.rand')
            ->with('users',[""=>'所有小編']+$users)
            ->withAnimations($rand_animations);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $agent = new Agent();
        //
        //dd(Request::server("HTTP_HOST"));
        $animation = new \stdClass();
        $animation_url = Config::get('app.master_url');
        $animation_ss_url = str_replace("http", "https", Config::get('app.master_url'));

        $animation->preview_url = url("/")  . "/animation_photos/preview/" . $animation->id . ".png";
        // $animation->swf_url=  $animation_url."/animation_photos/".$animation->id.".swf";
        $animation->og_url = url("/") . "/animation_photos/" . $animation->photo;
        /**iso时间**/
        $dt = Carbon::parse($animation->updated_at);
        $animation->updated_at_iso=$dt->toIso8601String();
        $dt = Carbon::parse($animation->created_at);
        $animation->created_at_iso=$dt->toIso8601String();
        if ($agent->isMobile()==true) $limit=5;else $limit=9;

        $rand_animations = Animation::orderBy("id", 'desc')->paginate($limit);
        $rand_animations->setPath(''); //重要

        $page = ['title' => '動圖列表', 'sub_title' => 'Getez-簡單新生活',
            'url' => url(''), 'share_url' => url(''), 'share_url' => url(''), 'photo' => url("/")."animation_photos/".$rand_animations[0]->photo];
        // where('photo','like','.gif')->
        return view('animations.index')->withAnimations($rand_animations)
            ->with('plan', 1)
            ->with('rand', $rand_animations)
            ->with('page', $page)
            ->with('mobile', $agent->isMobile())
            ;
    }
    public function show($id)
    {
        //dd('動圖 程式維護更新中');
        $agent = new Agent();
        //
        //dd(Request::server("HTTP_HOST"));
        $animation = new \stdClass();
        $animation = Animation::find($id);
        $animation_url = Config::get('app.master_url');
        $animation_ss_url = str_replace("http", "https",url("/"));

        $animation->preview_url =   $animation_ss_url  . "/animation_photos/preview/" . $animation->id . ".png";
        // $animation->swf_url=  $animation_url."/animation_photos/".$animation->id.".swf";
        $animation->og_url =   $animation_ss_url . "/animation_photos/" . $animation->photo;
        /**iso时间**/
        $dt = Carbon::parse($animation->updated_at);
        $animation->updated_at_iso=$dt->toIso8601String();
        $dt = Carbon::parse($animation->created_at);
        $animation->created_at_iso=$dt->toIso8601String();
        $rand_animations = Animation::orderByRaw("RAND()")->take(3)->get();

        $page = ['title' =>'GetEz!! fun大樂園', 'sub_title' => $animation->title,
            'url' => url(''), 'share_url' =>  $animation_ss_url.'/animations/'.$animation->id, 'photo' =>   $animation->og_url];       // $rand_articles =
        return view('animations.show')
            ->withAnimation($animation)
            ->with('plan', 1)
            ->with('page', $page)
            ->with('rand', $rand_animations)
            ->with('mobile', $agent->isMobile())
            ;
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Media;

use Input;
use Validator;
use Redirect;
use Request;
use Response;
//use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
/*增加部分*/
use App\Article, App\Category,App\User;
//use Redirect, Input;
use Auth;
use yajra\Datatables\Datatables;
use DB;
use Image;

class advController extends Controller{
    
    public function editor(){
        $status=2;
         $page = ['title' => '廣告系統', 'sub_title' => '廣告編輯',
            'url' => URL('admin/adv/editor')];

        return view('admin.adv.editor')->withPage($page)->with('title', 'ttt')->with('status', $status);

    }


    public function preview(){
        //return 'preview';
        $page = ['title' => '廣告系統', 'sub_title' => '廣告預覽',
            'url' => URL('admin/adv/preview')];

        $status=2;
        return view('admin.adv.preview')->withPage($page)->with('title', 'ttt')->with('status', $status);
    }


    public function editordata(){
        $adv = DB::table('adv')->get();

        return $adv;
    }


    public function get_ad(Datatables $datatables)
    {
        $data=[];
        $id = $datatables->request->id;
        $plan = $datatables->request->plan;
        $domain = $datatables->request->domain;
        
        
        $row = DB::table('adv')
        ->where('id','=', $id)
        ->select(['id'])
        ->first();

        if(!$row){
        
            DB::table('adv')->insert(
                [
                    'id'=>$id
                ]
            );

        }

         $row = DB::table('adv_code')
        ->where('adv_id','=', $id)
        ->where('plan','=', $plan)
        ->where('domain','=', $domain)
        ->select(['id'])
        ->first();

        if(!$row){
            DB::table('adv_code')->insert(
                [
                    'adv_id'=>$id,
                    'plan'=>$plan,
                    'domain'=>$domain
                ]
            );
        }
       
        $data = $this->get_ad_data($id, $plan, $domain);

        return $data;
    }


    public function get_ad_data($id, $plan, $domain){
        $data = DB::table('adv')
        ->join('adv_code', 'adv.id', '=', 'adv_code.adv_id')
        ->where('adv.id','=', $id)
        ->where('adv_code.plan', '=', $plan)
        ->where('adv_code.domain', '=', $domain)
        ->select(['adv.*', 'adv_code.*'])
        ->first();

        $data=(array) $data;
        return $data;
    }

    public function set_ad(Datatables $datatables){
        
        $name=$datatables->request->name;
        $plan=$datatables->request->plan;
        $id=$datatables->request->id;
        $code=$datatables->request->code;
        $domain=$datatables->request->domain;
        $code_onload=$datatables->request->code_onload;
        $rate=$datatables->request->rate;

        if(!$rate) $rate=0;

        DB::table('adv')
        ->where('id', $id)
        ->update(['name'=>'']);

        DB::table('adv_code')
        ->where('adv_id', $id)
        ->where('plan', $plan)
        ->where('domain', $domain)
        ->update(['name'=>$name, 'code'=>$code, 'code_onload'=>$code_onload, 'rate'=>$rate]);


        return [];
    }
}
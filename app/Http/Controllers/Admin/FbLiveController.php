<?php

namespace App\Http\Controllers\Admin;

use App\FbLive;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

use App\User,App\FbLiveLog;
use Auth,Input,Response;

use DB;
use Image;
class FbLiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index(Request $request)
    {


        $data=Input::all();
       /* $items = FbLive::select(DB::raw('fb_lives.*, count(fb_lives_collects.id) AS `collect_counts`'))
            ->leftjoin('fb_lives_collects', 'fb_lives_collects.av_user_id', '=', 'fb_lives.id')
            ->groupBy('fb_lives.id');*/

        $items=FbLive::with('author');


       // if (!empty($data['id']))  $items = $items ->where('id','=',''.$data['id'].'');
        if (!empty($data['fb_live_id']))  $items = $items ->where('id','like','%'.$data['fb_live_id'].'%');
        if (!empty($data['title']))  $items = $items ->where('title','like','%'.$data['title'].'%');
        /*
        if (!empty($data['answers'])) {
            $items = $items->where(function ($query) {
                $query->where('like_txt', 'like', '%' . Input::get('answer') . '%')
                    ->orwhere('love_txt','like', '%' . Input::get('answer') . '%')
                    ->orwhere('haha_txt','like', '%' . Input::get('answer') . '%')
                    ->orwhere('wow_txt','like', '%' . Input::get('answer') . '%')
                    ->orwhere('sad_txt','like', '%' . Input::get('answer') . '%')
                    ->orwhere('angry_txt','like', '%' . Input::get('answer') . '%')

                ;
            });

        }*/
        if (!empty($data['name']))  $items = $items ->where('created_user',''.$data['name'].'');
        if (!empty($data['fb_video_id']))  $items = $items ->where('fb_video_id','like','%'.$data['fb_video_id'].'%');
        if (!empty($data['created_at']))  $items = $items ->where('fb_lives.created_at','like','%'.$data['created_at'].'%');
        if (!empty($data['updated_at']))  $items = $items ->where('fb_lives.updated_at','like','%'.$data['updated_at'].'%');
       // if (!empty($data['collect_counts']))  $items = $items ->havingRaw('count(fb_lives_collects.id)='.$data['collect_counts'].'');


        if (!empty($data['sort_name'])) {
            $items = $items ->orderBy($data['sort_name'],$data['sort_order']);
        } else {
            $items = $items ->latest();
        }

        $items = $items ->paginate(10);
        /*
                foreach ($items as $k=>$val){
                echo $val->collects->count();
            }exit;*/
        return response()->json($items);
    }
    public function manageFbLiveAjax()
    {
        $users=User::lists('name', 'id')->toArray();
        $page = ['title' => '直播管理', 'sub_title' => '新增FB Live',
            'url' => ''];

        return view('admin.fb_lives.index')    ->with('users',[""=>'所有小編']+$users)->withPage($page);
    }
    public function auto_save()
    {
        $data=Input::all();
        if ($data["answers"]) $data["answers"]=implode(",",$data["answers"]);

//var_dump($data);
        if (!isset($data['id']) or empty($data['id'])){
            // $fblive = new FbLive();
            $data['created_user']=$data['updated_user']=Auth::user()->get()->id;
            $fblive=FbLive::create($data);
        } else {
            $data['updated_user']=Auth::user()->get()->id;
            $fblive= FbLive::find($data["id"]);
            $fblive->update($data);
        }
        return Response::json(['id'=> $fblive->id,'url'=>route('admin.fb_lives.show',$fblive->id),
            'action_url'=>route('admin.fb_lives.auto_save',$fblive->id)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = ['title' => '直播管理', 'sub_title' => 'FB Live 直播列表',
            'url' => ''];

        $data = ['title' => '','id'=>0];


        $fb_live=new FbLive();
        $fb_live->created_user= $fb_live->updated_user=Auth::user()->get()->id;
        $fb_live->save();
        return redirect(route('admin.fb_lives.edit',$fb_live->id));
        return view('admin.fb_lives.create', $data)->withPage($page);
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
    public function desc()
    {
        //
        $page = ['title' => '直播管理', 'sub_title' => 'FB Live 說明',
            'url' => ''];
        return view('admin.fb_lives.desc')->withPage($page);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $page = ['title' => '直播管理', 'sub_title' => '編輯 FB Live',
            'url' => ''];
        $emoji=['like','love','haha','wow','sad','angry'];
        $fb_live=FbLive::find($id);
        // dd($Fblive);\
        if ($fb_live->answers)$answers=explode(",",$fb_live->answers);else $answers=[];

        //文章選選

       // $fb_live->send_at= Carbon::parse($fb_live->send_at)->lt(Carbon::minValue()) ? "" : substr($fb_live->send_at,0,16);


        return view('admin.fb_lives.edit',$fb_live )->with('emoji',$emoji)->with('answers',$answers)->withPage($page);
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
        $fb_live =FbLive::find($id);


        $message = "[#".$fb_live->id." - ". $fb_live->title ." ]已刪除";
        $fb_live->delete();
        return redirect('admin/fb_lives/ajax' )->with('alert_message', $message);
    }
}

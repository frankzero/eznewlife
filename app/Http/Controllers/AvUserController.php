<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Requests;
use Request;
use App\Http\Controllers\Controller;
use App\Article, App\Category, App\User, App\AvUser,App\AvUserCollect,App\AvUserLog;
use Auth, Input;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use DB;
use ff;
use Cache;
use Response;
use Session,URL;
use Illuminate\Support\Facades\File;
use Validator;//很重要
use Redirect; //很重要
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
class AvUserController extends Controller
{
    public function log( ){
        $agent = new Agent();
        if (Auth::av_user()->check()){
            AvUserLog::create(['av_user_id'=>Auth::av_user()->get()->id,
                'session'=>Session::getId(),'agent'=>$agent->device(),'ip'=>\Illuminate\Support\Facades\Request::ip(),
                'refer'=>URL::previous(),'url'=>\Illuminate\Support\Facades\Request::url()
            ]);
            if (Auth::av_user()->get()->login_date!=date("Y-m-d")){

                $av=new AvUser();
                $av->setConnection('master');
                $av=$av->where('id', Auth::av_user()->get()->id)->first();
                $av->login_date=date("Y-m-d");
                $av->login_counts=Auth::av_user()->get()->login_counts+1;
                $av->save();
            }
        }
    }
    public function __construct(){
      //  $this->middleware('av_user');
    }
    public function check_repeat()
    {

        $data = Input::all();

        switch ($data["type"]) {

            case "update_email":
                $match = AvUser::where('email', "=", $data['email'])->where('email', "!=", Auth::av_user()->get()->email);
                break;
        }

        if ($match->count() > 0) {
            return 'false';
        } else {
            return 'true';
        }
        //create validator instance
    }
    protected function get_category()
    {
        /*** 取得類別資料**/
        $tmp_cache='av_categories';
        if (cache_has($tmp_cache)) {
            $categories  = cache_get($tmp_cache);
        } else {
            $categories= Category::getList();
            cache_forever($tmp_cache,  $categories);
        }
        return $categories;
    }
    public function profile(Request $request)
    {

        $this->log();
        $collects=av_user_info();
        $agent = new Agent();
        $expiresAt = Carbon::now()->addMinutes(5);
        $categories = $this->get_category();
        $page= ['title' => 'AvBody', 'sub_title' => '- 個人資料修改',
            'url' => url(''), 'share_url' => url(''), 'photo' => ''];
//dd($page);
        return view('av_users.profile')
            ->with('collects',$collects)
            ->with('plan', 2)
            ->with('mobile', $agent->isMobile())

            ->withPage($page)->with('categories', $categories);

    }

    public function paginate($items,$perPage,$count)
    {

        return new LengthAwarePaginator($items, $count, $perPage,Paginator::resolveCurrentPage('page'),
            array('path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page'));
    }
    public function collect_articles()
    {
        /*query->join('kg_shops', function($join)
        {
            $join->on('kg_shops.id', '=', 'kg_feeds.shop_id');

        })
            ->select('required column names')
            ->where('kg_shops.active', 1)
            ->get();
        */
        $data=Input::all();
        $av_user_id=Auth::av_user()->get()->id;
        $items = AvUserCollect::on('slave')
            ->join('articles', 'av_users_collects.article_id', '=', 'articles.id')
            ->where('av_user_id',$av_user_id)
        ->select(DB::raw('articles.id,articles.title,articles.score,articles.photo,av_users_collects.created_at as collected_date'));
      //  dd('score',($data['score']-1)*20,$data['score']*20);

        if (!empty($data['score']) and $data['score']!='all') {
            $diff=-1;
            if ($data['score']==5) $diff=0;
            $items = $items ->whereBetween('articles.score',[($data['score']-1)*20,$data['score']*20+$diff]);

        }

        if (!empty($data['title']))  $items = $items ->where('articles.title','like','%'.$data['title'].'%');
        if (!empty($data['created_at']))  $items = $items ->where('av_users_collects.created_at','like','%'.$data['created_at'].'%');
        switch ($data['sort_name']) {
            case "created_at":
                $items = $items ->orderBy("av_users_collects.".$data['sort_name'],$data['sort_order']);
                break;
            case "title":
                $items = $items ->orderBy("articles.".$data['sort_name'],$data['sort_order']);
                break;
            case "score":
                $items = $items ->orderBy("articles.".$data['sort_name'],$data['sort_order']);
                break;
            default:
                $items = $items ->orderBy('av_users_collects.id','desc');
                break;
        }

        $items = $items ->paginate(7);

        foreach ($items as $k=>$article){
            $items[$k]['unique_id']=get_unique_id($article->id);
        }

        /*$data=Input::all();
        $this->log();
        $default=(Input::get('sort_name'))? Input::get('sort_name') .".".Input::get('sort_order') : 'created_at.desc';
        $articles=av_user_info($default)->articles;
        $total_counts=count($articles);

        $limit=get_limit($total_counts,Input::get('page',1),7);
        $articles=array_slice($articles,(Input::get('page',1)-1)*7,$limit);
        $articles=$this->paginate(($articles), 7,$total_counts);*/
       // $items = $items ->paginate(3);
        /*
                foreach ($items as $k=>$val){
                echo $val->collects->count();
            }exit;*/
        //return false;
        return response()->json($items);
    }
    public function collect(Request $request)
    {
        $data=Input::all();

        $this->log();
        $agent = new Agent();
        $expiresAt = Carbon::now()->addMinutes(5);
        $categories =$this->get_category();
        $page= ['title' => 'AvBody', 'sub_title' => '我的收藏',
            'url' => url(''), 'share_url' => url(''), 'photo' => ''];
        if (Auth::av_user()->check()===false) return [];
        $collects=$user_collects=av_user_info();
        $created = new Carbon("2016-5-25");
        $now = Carbon::now();
        $startDate = "-".$created->diffInDays($now)."d";


        return view('av_users.collect')
            ->with('plan', 2)
            ->with('startDate', $startDate)
           // ->with('layout',$agent->isMobile()?'enl_mobile':'enl')
            ->with('collects',$collects)
            ->with('mobile', $agent->isMobile())
            ->withPage($page)->with('categories', $categories);

    }
    public function update(Request $request)
    {
        $data=Input::all();
        //$data = Input::only('name','email','password');
        $id= Auth::av_user()->get()->id;
        $rules=[
            'nick_name' => 'required',
            'email' => 'email|max:255|unique:av_users,email,' . $id,
        ];

        $validator = Validator::make($data, $rules);


        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
           ;
            $user = AvUser::on('master')->find($id);
            unset($data['avatar']);
            $user->update($data);
            if (Request::hasFile('avatar')) {
                //

                $file = Request::file('avatar');
                $extension = strtolower($file->getClientOriginalExtension());

//dd($file);
                $imgData = base64_encode(file_get_contents($file));

                // Format the image SRC:  data:{mime};base64,{data};
                $src = 'data: '.$file->getMimeType().';base64,'.$imgData;


                /*
                $avatar_point=strpos($user->avatar, '/av_avatar/');
                if ($avatar_point>0) {
                    $avatar=substr($user->avatar,$avatar_point);
                }
                $destinationPath = public_path() . '/av_avatar/';
                $original_photo = $destinationPath . $avatar;
                if (File::exists($original_photo) and !empty($user->avatar)) {
                    File::delete($original_photo);
                }
                $fileName = $user->id . '.' . $extension;
                $upload_success = $file->move($destinationPath, $fileName);
                */

                $user->avatar =$src;
                $user->save();

            }


            $message="資訊已修改";
            return redirect('me/profile')->with('message', $message);
        }

    }
    public function destory(Request $request)
    {
        $av_user_id= Auth::av_user()->get()->id;
        $data = Input::all();
        AvUserCollect::where('av_user_id',  $av_user_id)->where('article_id',$data['article_id'])->delete();
       // set_av_user_info();
        $message="收藏文章已移除";
        return redirect('me/collect'."?page=".$data['page'])->with('message', $message);
    }
}
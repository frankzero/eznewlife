<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Requests;
use Request;
use App\Http\Controllers\Controller;
use App\Article, App\Category, App\User, App\EnlUser,App\EnlUserCollect;
use Auth, Input;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use DB;
use ff;
use Cache;
use Response;
use Illuminate\Support\Facades\File;
use Validator;//很重要
use Redirect; //很重要
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
class EnlUserController extends Controller
{

    public function __construct(){
      //  $this->middleware('enl_user');
    }
    public function check_repeat()
    {

        $data = Input::all();

        switch ($data["type"]) {

            case "update_email":
                $match = EnlUser::where('email', "=", $data['email'])->where('email', "!=", Auth::enl_user()->get()->email);
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
        $tmp_cache='enl_categories';
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
        $agent = new Agent();
        $expiresAt = Carbon::now()->addMinutes(5);
        $categories = $this->get_category();
        $page= ['title' => 'EzNewlife', 'sub_title' => '簡單新生活 - 個人資料修改',
            'url' => url(''), 'share_url' => url(''), 'photo' => ''];

        return view('enl_users.profile')
            ->with('plan', 1)
            ->with('mobile', $agent->isMobile())
            ->with('layout',$agent->isMobile()?'enl_mobile':'enl')

            ->withPage($page)->with('categories', $categories);

    }
    public function paginate($items,$perPage,$count)
    {

        return new LengthAwarePaginator($items, $count, $perPage,Paginator::resolveCurrentPage('page'),
            array('path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page'));
    }
    public function collect(Request $request)
    {

        $agent = new Agent();
        $expiresAt = Carbon::now()->addMinutes(5);
        $categories =$this->get_category();
        $page= ['title' => 'EzNewlife', 'sub_title' => '簡單新生活 - 我的收藏',
            'url' => url(''), 'share_url' => url(''), 'photo' => ''];
        if (Auth::enl_user()->check()===false) return [];
        $user_collects=enl_user_info();
        /**取得收藏文章*/

        $collect_ids= array_keys($user_collects->user_collects->toArray());
        $limit=get_limit(count($collect_ids),Input::get('page',1),5);
        $collect_ids_data=array_slice( $collect_ids,-5*Input::get('paged',1),$limit);
        $user_collect_articles=[];
        foreach ($collect_ids_data as  $k=>$article_id){
            if (cache_has('article_' . $article_id)) {

                $user_collect_articles[$k]= cache_get('article_' . $article_id);
                $user_collect_articles[$k]->created_at=$user_collects->user_collects[$article_id];
            }
        }
        $user_collects=$this->paginate(array_reverse($user_collect_articles), 5,count($collect_ids));
      //  $user_collects=EnlUserCollect::where('enl_user_id',Auth::enl_user()->get()->id)->groupBy('article_id')->paginate(5);//->articles();//->with('articles')->get();;
      // dd( $user_collect_articles);
       /* if (cache_has($tmp_cache)) {
            $articles = cache_get($tmp_cache);
        } else {
           // $collect=Auth::enl_user()->get()->collects()->lists('created_at','article_id');
            //SELECT * FROM `enl_users_collects`,articles WHERE enl_user_id='1' and article_id=articles.id
            // echo $collect;

            $articles=App\Article::with('ez_map')->whereIn('id',array_keys($collect->toArray()))->paginate(5);
            $articles->user_collects=$collect;
            cache_put($tmp_cache, $articles, $expiresAt);
        }*/
        return view('enl_users.collect')
            ->with('plan', 1)
            ->with('layout',$agent->isMobile()?'enl_mobile':'enl')
            //->with('collects',$collects)
            ->with('user_collects',$user_collects)
            ->with('mobile', $agent->isMobile())
            ->withPage($page)->with('categories', $categories);

    }
    public function update(Request $request)
    {
        $data=Input::all();
        //$data = Input::only('name','email','password');
        $id= Auth::enl_user()->get()->id;
        $rules=[
            'nick_name' => 'required',
            'email' => 'email|max:255|unique:enl_users,email,' . $id,
        ];

        $validator = Validator::make($data, $rules);


        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
           ;
            $user = EnlUser::find($id);
            unset($data['avatar']);
            $user->update($data);
            if (Request::hasFile('avatar')) {
                //

                $file = Request::file('avatar');
                $extension = strtolower($file->getClientOriginalExtension());
                $avatar_point=strpos($user->avatar, '/avatar/');
                if ($avatar_point>0) {
                    $avatar=substr($user->avatar,$avatar_point);
                }
                $destinationPath = public_path() . '/avatar/';
                $original_photo = $destinationPath . $avatar;
                if (File::exists($original_photo) and !empty($user->avatar)) {
                    File::delete($original_photo);
                }
                $fileName = $user->id . '.' . $extension;
                $upload_success = $file->move($destinationPath, $fileName);

                $user->avatar = cdn('avatar/'.$fileName);
                $user->save();

            }


            $message="資訊已修改";
            return redirect('me/profile')->with('message', $message);
        }

    }
    public function destory(Request $request)
    {
        $enl_user_id= Auth::enl_user()->get()->id;
        $data = Input::all();
        EnlUserCollect::where('enl_user_id',  $enl_user_id)->where('article_id',$data['article_id'])->delete();
       // set_enl_user_info();
        $message="收藏文章已移除";
        return redirect('me/collect'."?page=".$data['page'])->with('message', $message);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use Request;
//use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Animation,App\User;
use Auth, Input, Response, Mail, Validator;
use yajra\Datatables\Datatables;
use DB;
use Image;
use Config;
use Redirect;

class AnimationController extends Controller
{

    public function getIndex()
    {
       // dd('動圖 程式維護更新中');

        $page = ['title' => '動圖管理', 'sub_title' => '',
            'url' => route('datatables.animations')];

        $feedback=json_encode([""=>'ALL',"0"=>'BUG',"1"=>'ON']);
        $photo_type=json_encode([""=>'ALL',".gif"=>'GIF',".mp4"=>'MP4']);
        $users = json_encode(User::lists('name', 'id'));

        if (Input::has("source")) $source = Input::get('source'); else $source = "enl";

        return view('admin.animations.datatables_filter')->withPage($page)
            ->with('users', $users)
            ->with('feedback', $feedback)
            ->with('photo_type', $photo_type);

    }

    public function postData(Datatables $datatables)
    {

        //dd('動圖 程式維護更新中');

        //  if (Input::get('tag'))
        $request = Input::get('columns');
        //  $value=$request[8]['search']['value'];

        $animations = Animation::join('users as u1', 'animations.created_user', '=', 'u1.id')// ['created_by']
        ->join('users as u2', 'animations.updated_user', '=', 'u2.id')// ['modified_by']
        ->select(['animations.id', 'title', 'u1.name as author',DB::raw("upper(substr(animations.photo,-3,3)) as photo_type"), 'u2.name as updater', 'animations.photo', 'animations.photo_size',  'animations.feedback', 'animations.photo_width', 'animations.photo_height','animations.updated_at']);
        /**
         * 預設 排序 order[0][column]=1&order[0][dir]=asc
         * 因為 使用ajax sql 所以 當order[0][column]=0 (第一欄)
         * 預設根據更新日期排序
         *  $datatables = $datatables->eloquent($animations);
         */
        if ($datatables->request->order[0]['column'] == 0) {
            $animations->orderByRaw('animations.id desc');
        }
        // dd($animations->get());
        $value = '';
        if (isset($request[2]['search']['value'])) $value = $request[2]['search']['value'];
        return Datatables::of($animations)
            ->filterColumn('author', 'whereRaw', "`u1`.`name` like ? ", ["$1"])
            ->filterColumn('updater', 'whereRaw', "`u2`.`name` like ? ", ["$1"])
            ->filterColumn('id', 'whereRaw', "`animations`.`id` like ? ", ["$1"])
            //->filterColumn('tags', 'whereRaw', "`u2`.`name` like ? ", ["$1"])
            ->editColumn('feedback', function ($article) {
                if ($article->feedback == 1) {
                    $feedback = '<span class="label label-success">ON</span>';
                } else{
                    $feedback = '<span class="label label-warning">BUG</span>';
                }

                return $feedback;
            })
            ->editColumn('photo_size', function ($animation) {
                $photo_size=round($animation->photo_size/1024,2);
                $tmp_class='';
                if ($photo_size>8) $tmp_class="text-danger";
                $extension = strtoupper(public_path() . '/animation_photos'."/".$animation->photo);

                $extension=strtoupper(File::extension($animation->photo));
                return "<span class='".$tmp_class."'>".$photo_size." MB</span>"."<br><span class='".$tmp_class."'>".$extension." </span>";
            })
            ->editColumn('photo_width', function ($animation) {
                $tmp_class = '';
                if ($animation->photo_width<200 and $animation->photo_width>0) {
                    $photo_ratio = round($animation->photo_width / $animation->photo_height, 2);
                    if ($photo_ratio!=1) {
                        $tmp_class = 'text-danger';
                    }
                }


                //if ($photo_ratio>1.5 or $photo_ratio<0.5) $tmp_class="text-danger";
                return  "<span class='".$tmp_class."'>".$animation->photo_width.'</span>';
            })
            ->editColumn('photo_height', function ($animation) {
                $tmp_class = '';
                if ($animation->photo_height<200 and $animation->photo_height>0) {
                    $photo_ratio = round($animation->photo_width / $animation->photo_height, 2);
                    if ($photo_ratio!=1) {
                        $tmp_class = 'text-danger';
                    }
                }


                //if ($photo_ratio>1.5 or $photo_ratio<0.5) $tmp_class="text-danger";
                return  "<span class='".$tmp_class."'>".$animation->photo_height.'</span>';
            })
            ->editColumn('photo_ratio', function ($animation) {
                if ($animation->photo_height>0) {
                    $photo_ratio = round($animation->photo_width / $animation->photo_height, 2);
                } else $photo_ratio=0;
                $tmp_class = '';

                //if ($photo_ratio>1.5 or $photo_ratio<0.5) $tmp_class="text-danger";
                return  "<span class='".$tmp_class."'>".$photo_ratio.'</span>';
            })
            ->editColumn('thumbnail', function ($animation) {
                if (File::exists(public_path() . '/animation_photos' . "/" . $animation->photo) and !empty($animation->photo)) {

                    if (strpos($animation->photo,"mp4")){
                       return ' <video width="73" height="49" preload="auto" autoplay="autoplay" muted="muted" loop="loop" webkit-playsinline controls>
                            <source src="'. asset("animation_photos/" . $animation->photo). '?lastmod=' . $animation->updated_at .'" type="video/mp4" >
                                                Your browser does not support the video tag.
                        </video>';

                    } else {
                        return
                            '<a href="' . URL('admin/animations/' . $animation->id . '/edit') . '" class="popoverthis" data-toggle="popover"  rel="nofollow"  '
                            . 'data-content=\'' . '<img src="' . asset("animation_photos/" . $animation->photo) . '?lastmod=' . $animation->updated_at . '"'
                            . ' class="img-responsive" width="300px">\' title="' . $animation->title . '" />' . '<img src="' . asset("animation_photos/" . $animation->photo) . '?lastmod=' . $animation->updated_at . '"'
                            . ' class="img-animation ">' . '</a>';
                    }
                } else {
                    return '<a href="' . URL('admin/animations/' . $animation->id . '/edit') . '"  title="#' . $animation->id . ' 編輯">' . $animation->title . "</a>";
                }

            })
            ->addColumn('action', function ($animation) {


                $animation_url = Config::get('app.animation_url');
                if  (strpos($animation->photo,"mp4")) {
                    $animation_ss_url= str_replace("http","https",Config::get('app.master_url'));
                    $animation_my_url=$animation_ss_url."/animations/".$animation->id;
                    $animation_url['ENL']=str_replace("http","https",$animation_url['ENL']) ;
                    $tmp_url='https://developers.facebook.com/tools/debug/og/object/?q=' . rawurlencode( $animation_my_url);
                    $share_url='https://www.facebook.com/sharer/sharer.php?u=' .rawurlencode( $animation_my_url);
                } else {
                    $tmp_url = 'https://developers.facebook.com/tools/debug/og/object/?q=' . rawurlencode($animation_url['ENL'] . '/animation_photos/' . $animation->photo);
                    $share_url = 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode($animation_url['ENL'] . '/animation_photos/' . $animation->photo) . "&description=" . rawurlencode($animation->title);
                }
                if ($animation->feedback==1) {
                    $reply="回報bug狀態";
                    $feedback=0;
                } else {
                    $reply = "改回正常狀態";
                    $feedback=1;
                }
                return
                 ' <a href="#" id="' . $animation->id . '" new_feedback="' . $feedback . '"  class="btn btn-default btn-xs small-btn" rel="nofollow"  data-toggle="tooltip" '
                . 'data-placement="bottom" data-original-title="'.$reply.'" onclick="databases_reply_confirm(this.id,'.$feedback.')" class="reply_confirm">'
                . '<i class=" fa  fa-flag"></i></a>'

                .'<a href="'.$share_url.'" target="_facebook" data-toggle="tooltip" data-placement="bottom"  class="btn btn-default btn-xs small-btn" data-original-title="立即分享" rel="nofollow" ><i class=" fa  fa-facebook"></i></a>'
                .      '<a href="'.$tmp_url.'" target="_facebook" data-toggle="tooltip" data-placement="bottom"  class="btn btn-default btn-xs small-btn" data-original-title="Facebook Debugger" rel="nofollow" ><i class=" fa   fa-bug"></i></a>'

                      .'<a href="' . URL('admin/animations/' . $animation->id . '/edit') . ' " style="color:rgba(8, 4, 4, 0.99)"
                 class="btn btn-default btn-xs small-btn" rel="nofollow"  data-toggle="tooltip"  target="edit_' . $animation->id . '" data-placement="bottom" data-original-title="修改"
                ><i class=" fa  fa-pencil-square-o"></i></a>'
                . ' <a href="#" id="' . $animation->id . '" class="btn btn-default btn-xs small-btn" rel="nofollow"  data-toggle="tooltip" '
                . 'data-placement="bottom" data-original-title="刪除" onclick="databases_delete_confirm(this.id)" class="delete_confirm">'
                . '<i class=" fa  fa-trash-o"></i></a>';

            })
            ->make(true);


    }
    public function reply(Request $request, $id)
    {

        $data = Input::all();
        $animation = Animation::find($id);
        $animation->update($data);

    }
    public function auto_save(Request $request)
    {
        //return  response()->json(Input::all());
        // $data=Input::all();

        $data = Input::all();
        $rules = array(

            'photo' => 'image',

            // 'publish_at' => 'required'
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            if (isset($data["id"]) and !empty($data["id"])) {
                return Response::json(['error' => 'Image Error', 'id' => $data["id"],
                    'url' => '',
                    'action_url' => route('admin.animations.auto.update', $data["id"])
                ]);
            } else {
                return Response::json(['error' => 'Image Error', 'id' => '0', 'url' => '',
                    'action_url' => '']);
            }
        }


        $data['title'] = title_optimize($data["title"]);
        /**
         * 先不存photo
         * */
        unset($data['photo']);
        if (!isset($data['id']) or empty($data['id'])) {
            // $animation = new Animation();
            $data['created_user'] = $data['updated_user'] = Auth::user()->get()->id;
            $animation = Animation::create($data);
        } else {
            $data['updated_user'] = Auth::user()->get()->id;
            $animation = Animation::find($data["id"]);
            $animation->update($data);
        }

        if (Request::hasFile('photo')) {
            //

            $file = Request::file('photo');
            $extension = strtolower($file->getClientOriginalExtension());

            $destinationPath = public_path() . '/animation_photos/';
            $original_photo = $destinationPath . $animation->photo;
            if (File::exists($original_photo) and !empty($animation->photo)) {
                File::delete($original_photo);
            }
            $fileName = $animation->id . '.' . $extension;
            $upload_success = $file->move($destinationPath, $fileName);

            $animation->photo = $fileName;

        }
        $animation->save();
        $animation_url = Config::get('app.animation_url');
        if (!empty($animation->photo)) {
            foreach ($animation_url as $site_name => $website) {
                $tmp_url[$site_name] = $website . "/animation_photos/" . $animation->photo;
            }

            $animation->animation_url = $tmp_url;
        } else {
            $animation->animation_url = '';
        }


        return Response::json(['id' => $animation->id, 'url' => "",
            'action_url' => route('admin.animations.auto.update', $animation->id), 'photo_url' => $animation->animation_url]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $page = ['title' => '動圖管理', 'sub_title' => '新增動圖',
            'url' => route('datatables.animations')];

        $data = ['title' => ''];

        return view('admin.animations.create', $data)->withPage($page);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //  dd($request);


        $data = Input::all();

        $rules = array(
            /*'title' => 'required',*/
            'photo' => 'image',
        );
        //

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = Input::only(['title','stand']);
        $data['title'] = title_optimize($data['title']);
        //dd($data);
        $animation = new Animation();

        $animation->title = $data['title'];

        $animation->created_user = $animation->updated_user = Auth::user()->get()->id;

        /**
         * 先不存photo
         * */
        unset($data['photo']);
        if ($animation->save()) {
            if (Request::hasFile('photo')) {
                //

                $file = Request::file('photo');
                $extension = strtolower($file->getClientOriginalExtension());
                //顯示路徑
                $destinationPath = public_path() . '/animation_photos/';
                //原始檔
                $org_path = public_path() . '/animation_photos/org/';
                $org_preview_path  = public_path() . '/animation_photos/preview/';
                //資料庫原圖
                $db_photo = $destinationPath . $animation->photo;
                $db_org_photo = $org_path . $animation->org_photo;
                $db_preview_photo = $org_preview_path .$animation->id.".png";
                if (File::exists($db_photo) and !empty($animation->photo)) {
                    File::delete($db_photo);
                }
                if (File::exists($db_org_photo) and !empty($animation->org_photo)) {
                    File::delete($db_org_photo);
                }
                if (File::exists( $db_preview_photo) and !empty($animation->photo)) {
                    File::delete($ $db_preview_photo);
                }
                $fileName = $animation->id . '.' . $extension;

                //圖片上傳
                $upload_success = $file->move($org_path, $fileName);
                $org_photo = $org_path . $fileName;
               // dd($data);
                $result=animation($animation->id,$org_photo,$data['stand']);
              //  dd($result);
                $animation->photo_size=$result->size;
                //if (($animation->photo_size/1024)>8) $animation->feedback=0;
                $animation->photo_width=$result->width;
                $animation->photo_height=$result->height;
                $animation->photo = $result->source;
                $animation->org_photo=$fileName;
                $animation->save();
            }

            $message = "動圖#" . $animation->id . " " . $data['title'] . "已新增<br> ";
            if (($animation->photo_size/1024)>8)    $message .= "動圖#" . $animation->id ."大於8MB 分享可能有問題喔!!";

            return redirect(route('admin.animations.edit', $animation->id))->with('message', $message);
            // return Redirect('admin',$message);
        } else {
            return Redirect::back()->withInput()->withErrors('存入資料失敗！');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //dd('動圖 程式維護更新中');

        $page = ['title' => '動圖管理', 'sub_title' => '編輯動圖',
            'url' => route('datatables.animations')];
        $animation = Animation::find($id);
        $animation_url = Config::get('app.animation_url');
        if (!empty($animation->photo)) {
            foreach ($animation_url as $site_name => $website) {
                $tmp_url[$site_name] = $website . "/animation_photos/" . $animation->photo;
            }

            $animation->animation_url = $tmp_url;
        } else {
            $animation->animation_url = '';
        }
        return view('admin.animations.edit', $animation)->withPage($page);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $data = Input::all();

        $rules = array(
            'photo' => 'image',

        );
        //


        $data['title'] = title_optimize($data["title"]);

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $animation = Animation::find($id);

        $animation->title = $data['title'];
        /**
         * 先不存photo
         * */
        unset($data['photo']);
        if ($animation->update($data)) {


            if (Request::hasFile('photo')) {
                //

                $file = Request::file('photo');
                $extension = strtolower($file->getClientOriginalExtension());
                //顯示路徑
                $destinationPath = public_path() . '/animation_photos/';
                //原始檔
                $org_path = public_path() . '/animation_photos/org/';
                $org_preview_path  = public_path() . '/animation_photos/preview/';
                //資料庫原圖
                $db_photo = $destinationPath . $animation->photo;
                $db_org_photo = $org_path . $animation->org_photo;
                $db_preview_photo = $org_preview_path .$animation->id.".png";
                if (File::exists($db_photo) and !empty($animation->photo)) {
                    File::delete($db_photo);
                }
                if (File::exists($db_org_photo) and !empty($animation->org_photo)) {
                    File::delete($db_org_photo);
                }
                if (File::exists( $db_preview_photo) and !empty($animation->photo)) {
                    File::delete($db_preview_photo);
                }

                $fileName = $animation->id . '.' . $extension;

                //圖片上傳
                $upload_success = $file->move($org_path, $fileName);
                $org_photo = $org_path . $fileName;
                $result=animation($animation->id,$org_photo,$data['stand']);
                //dd($result);
                $animation->photo_size=$result->size;
                $animation->photo_width=$result->width;
                $animation->photo_height=$result->height;
                $animation->photo = $result->source;
                $animation->org_photo=$fileName;
                //if (($animation->photo_size/1024)>8) $animation->feedback=0;

                $animation->save();
            }

            $message = "動圖#" . $animation->id . " " . $data['title'] . "已編輯 <br> ";
            if (($animation->photo_size/1024)>8)    $message .= "動圖#" . $animation->id ."大於8MB 分享可能有問題喔!!";


            return redirect(route('admin.animations.edit', $animation->id))->with('message', $message);
        } else {
            return Redirect::back()->withInput()->withErrors('保存失败！');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        //dd($id);
        $animation = Animation::find($id);

        $photo_dir = public_path() . '/animation_photos/';
        $org_dir = public_path() . '/animation_photos/org/';
        $preview_dir = public_path() . '/animation_photos/preview/';

        $original_photo = $org_dir . $animation->org_photo;
        $my_photo = $photo_dir . $animation->photo;
        $prev_photo = $preview_dir . $animation->id.".png";
        //  dd($original_photo);
        /*
   * 10/29 frank 說要保留舊圖
  if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo)) {
      File::delete(public_path() . '/focus_photos'."/".$article->photo);
  }
  */
        if (File::exists($original_photo) and !empty($animation->org_photo)) {

            File::delete($original_photo);
        }
        if (File::exists($my_photo) and !empty($animation->photo)) {

            File::delete($my_photo);
        }
        if (File::exists($prev_photo ) ) {

            File::delete($prev_photo );
        }
        $animation->delete();
        $message = $animation->title . " 動圖資訊已刪除";
        return redirect('admin/animations/datatables')->with('message', $message);
    }
}

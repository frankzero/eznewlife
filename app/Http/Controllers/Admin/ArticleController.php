<?php

namespace App\Http\Controllers\Admin;


use App\ArticleMap;
use App\AvUserCollect;
use App\Media;

use App\Parameter;
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
use Auth,Cache;
use yajra\Datatables\Datatables;
use DB;
use Image,Config;
use ff;

class ArticleController extends Controller
{
    public function auto_save(Request $request)
    {
        //return  response()->json(Input::all());
        // $data=Input::all();
        //編輯中的草稿文章 可以為即時文章…但不會放到rss 裡
        
        //return Response::json($response);


        timer();


        $data = Input::all();



        //$validator2 = Validator::make($data,['publish_at'        => 'date_format:Y-m-d H:i:s']);
        //自動緩存格式錯誤 改為 null
        if (Validator::make($data,['publish_at'  => 'date_format:Y-m-d H:i'])->fails()) {
            $data['publish_at']=null;
        }
        $publish = Carbon::parse($data['publish_at']);

        $now = Carbon::now();



        if (empty($data['publish_at'])){
            $data['status'] = 0;
        }elseif ($now->gte($publish)) {
            $data['status'] = 1;
        } else {
            $data['status'] = 2;
        }
        if (isset($data['instant'])){
            $data['instant']='1';
        }
        if (isset($data['flag'])){
            $data['flag']='P';
            $data['instant']='0';
        } else {
            $data['flag']='G';
        }

        $data['title']=title_optimize($data["title"]);

        //去掉空圖

        /**去空圖*/


        $data['content']=trim_img($data["content"]);


        //替avbody加屬性
        if ($data['category_id']=='9'){
            $data["content"]=add_article_attribute($data['content'],'content','read');
        }


        unset($data['photo']);


        $old=array();$old_cid='';

        $isTagChanged = false;

        if (!isset($data['id']) or empty($data['id'])){
            // $article = new Article();
            $response["message"]='error';
            $response["redirect"]=    '/admin/articles/list?length=100&is_deleted=0&page=1&dir=DESC';
            return $response;
            return Response::json($response);
            /*
            $data['created_user']=$data['updated_user']=Auth::user()->get()->id;

            $article=Article::create($data);

            $article_map=new ArticleMap();
            $article_map->articles_id=$article->id;
            $article_map->site_id=1;

            $article_map->save();
            $article_map->unique_id=$article_map->id;//$unique_id=ArticleMap::max('unique_id')+1;
            $article_map->save();
            $unique_id=    $article_map->unique_id;
            if (!empty($data['tags'])) $this->tag_preprocess($data['tags'],$article);
            $article = Article::with('tagged','ez_map')->find($article->id);

            $isTagChanged=true;
            */

        } else {


           // return redirect('http://admin.eznewlife.com/admin/articles/list?length=100&is_deleted=0&page=1&dir=DESC');
            $data['updated_user']=Auth::user()->get()->id;
            $article = Article::with('tagged','ez_map')->find($data["id"]);
            $old=$article->tags->pluck('name')->all();
            
            $old_cid=$article->category_id;
            $old_publish_at = $article->publish_at;

            //判斷tag 是否有修改過
            if($this->isTagChanged($data['tags'], $article->Tagged)){
                $isTagChanged = true;
            }else{
                $isTagChanged = false;
               
            }

            $article->update($data);

            if (!empty($data['tags'])) {
                $this->tag_preprocess($data['tags'],$article);
            } else {
                $article->untag();
            }

            $unique_id=$article->ez_map[0]->unique_id;

        }


        


        if (Request::hasFile('photo')) {
            //

            $file = Request::file('photo');
            $extension = strtolower($file->getClientOriginalExtension());
            $destinationPath = public_path() . '/focus_photos/';
            //  $smallPath=public_path() . '/focus_photos/200/';
            $one_Path = public_path() . '/focus_photos/100/';
            $four_Path = public_path() . '/focus_photos/400/';
            $thumbnail = ['400' => $four_Path,'100' => $one_Path ];
            $do_image_path=$destinationPath . $article->photo;
            $do_image_type=File::extension($do_image_path);

            $original_photo = $destinationPath . $article->photo;
            if (File::exists($destinationPath. $article->photo) and !empty($article->photo)) {
                File::delete($destinationPath. $article->photo);
            }
            foreach ($thumbnail as $key=>$thumbnail_path) {
                if (File::exists($thumbnail_path . $article->photo) and !empty($article->photo)) {
                    File::delete($thumbnail_path . $article->photo);
                }
            }
            $fileName = $article->id . '.' . $extension;
            $upload_success = $file->move($destinationPath, $fileName);
            $do_image= Image::make($destinationPath . $fileName);
            foreach ($thumbnail as $key => $val) {
                $do_image->resize($key, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($val . $fileName);

            }
            $do_image->destroy();
            // Storage::disk('local')->put($article->id.'.'.$extension,  File::get($file));
            $article->photo = $fileName;
            $article->save();
        }
        if (cache_has('article_'.$unique_id )) {
            cache_forget('article_'.$unique_id);
        }
        if (!empty( $article->photo)) {
            $article->photo_url = Config::get('app.master_url') . "/focus_photos/" . $article->photo;
        }else $article->photo_url='';


        Article::whereIn('category_id',Config::get('app.porn_categories'))->update(array('flag' =>'P'));



        $master_url = Config::get('app.master_url');
        $domain_url = Config::get('app.domain');


        $article_url=$master_url.'/'.$article->ez_map[0]->unique_id ."/". hyphenize($article->title);
        $debug_url='https://developers.facebook.com/tools/debug/og/object/?q='.rawurlencode($article_url);
        $share_url='https://www.facebook.com/sharer/sharer.php?u='.rawurlencode($article_url)."&description=".rawurlencode($article->title);
        $response=
        ['id'=>$article->id,'unique_id'=>$article->ez_map[0]->unique_id,
            'url'=>route('articles.show',[$article->ez_map[0]->unique_id,hyphenize($article->title)]), //admin.eznewlife.com
            'action_url'=>route('admin.articles.auto.update',$article->id),
            'photo_url'=> $article->photo_url,
            'article_url'=>$article_url,//eznewlife.com/123/XXX
            'debug_url'=>$debug_url,
            'share_url'=>$share_url,
            'instant_url'=>route('articles.instant',$article->ez_map[0]->unique_id),
            'mastre_url'=>$master_url,
            'flag'=>json_encode($data)];
        //記得 public/js/admin_main.js
        foreach ($domain_url as $site =>$site_url){
            if ($site=="ENL" or $site=="Getez") $site_title= "/".hyphenize($article->title); else $site_title="";
            $response[$site]="http://".$site_url."/".$article->ez_map[0]->unique_id.$site_title;
        }

        $doPublish = false;

        if ( $old_publish_at ==='0000-00-00 00:00:00' && $article->publish_at!==''  ){

            $doPublish=true;
        }

        

        if($isTagChanged === true || $doPublish === true ){

            foreach (explode(',',$data['tags']) as $k=>$v){

                save_tag_cache($v);
            }
            foreach ($old as $k=>$v){
                save_tag_cache($v);
            }
        }
       save_article_cache($article->id);
       // $all_categories=Category::lists('id');

       // $category_id=;
        if ($old_cid != $article->category_id || $doPublish === true  ){
            save_cate_cache($old_cid);
            save_cate_cache($article->category_id);
        }


        $response['time'] = timer();

        return Response::json($response);
    }


    public function all_cache($i){
        //$this->save_cate_cache(1);

        $loop=500;
        $all_articles = Article::lists('id');//草稿也能cache publish()->
        $count=$all_articles->count();
        echo $count."篇文章，";
        $times=ceil($count / $loop);
        echo "文章要跑".$times."次<br>";
        echo "參數設定檔重新產生=>".",<a href='".route('admin.all.cache','para')."' target='_blank' >".route('admin.all.cache','para')."</a><br>";
        echo "enl tag設定檔重新產生=>".",<a href='".route('admin.all.cache','tag').'?type=enl'."' target='_blank' >".route('admin.all.cache','tag')."?type=enl</a><br>";
        echo "dark tag設定檔重新產生=>".",<a href='".route('admin.all.cache','tag').'?type=dark'."' target='_blank' >".route('admin.all.cache','tag')."?type=dark</a><br>";
        echo "<span style='color:red'>文章目錄設定檔重新產生=></span>".",<a href='".route('admin.all.cache','cate')."' target='_blank' >".route('admin.all.cache','cate')."</a><br>";

        for($j=0;$j<$times;$j++){
          //  if ($i==j) $style="style='color:red'"; else $style='';

            if (($j+1)==$times){
                echo "<span style='color:red'>第".($j+1)."次</span><a href='".route('admin.all.cache',$j)."' target='_blank' >".route('admin.all.cache',$j)."</a><br>";
            } else {
                echo "第".($j+1)."次<a href='".route('admin.all.cache',$j)."' target='_blank' >".route('admin.all.cache',$j)."</a><br>";

            }
        }
        if ($i=='getezlist' ) {
            $articles = Article::getez()->publish()->where('content', 'not like', '%width=%')->where('content', 'like', '%src=%')->where('content', 'like', '%src=%')->lists('id');
            echo  $articles->count()."<hr>";
            foreach ($articles as $k => $id) {
                echo "getez =>" . $id . ",<a href='" . route('admin.all.cache', 'getez') . "?id=" . $id . "' target='_blank' >" . route('admin.all.cache', 'getez') . "?id=" . $id . "</a><br>";
            }
        }elseif ($i=='enllist' ) {

            $articles= Article::enl()->publish()->where('content', 'not like', '%width=%')->where('content', 'like', '%src=%')->lists('id');
            echo  $articles->count()."<hr>";
            foreach ($articles as $k => $id) {
                echo "enl =>".$id.",<a href='".route('admin.all.cache','enl')."?id=".$id."' target='_blank' >".route('admin.all.cache','enl')."?id=".$id."</a><br>";
            }
        }elseif ($i=='comiclist' ) {
             $articles= Article::avbody()->publish()->where('content', 'not like', '%width=%')->lists('id');
             foreach ($articles as $k => $id) {
                 echo "comic =>".$id.",<a href='".route('admin.all.cache','comic')."?id=".$id."' target='_blank' >".route('admin.all.cache','comic')."?id=".$id."</a><br>";
            }
        }elseif  ($i=='darklist' ) {
                $articles= Article::dark()->publish()->where('content', 'not like', '%width=%')->lists('id');
                foreach ($articles as $k => $id) {
                    echo "dark =>".$id.",<a href='".route('admin.all.cache','dark')."?id=".$id."' target='_blank' >".route('admin.all.cache','dark')."?id=".$id."</a><br>";
                }
        }elseif ($i=='enl' and !empty($_GET["id"])) {
            // $articles= Article::avbody()->lists('id');
            // foreach ($articles as $k => $id) {
            echo add_article_attribute($_GET["id"],'id','save');
        }elseif ($i=='getez' and !empty($_GET["id"])) {
            // $articles= Article::avbody()->lists('id');
            // foreach ($articles as $k => $id) {
            echo add_article_attribute($_GET["id"],'id','save');
        }elseif ($i=='dark' and !empty($_GET["id"])) {
            // $articles= Article::avbody()->lists('id');
            // foreach ($articles as $k => $id) {
            add_article_attribute($_GET["id"],'id','save');
        }elseif ($i=='comic' and !empty($_GET["id"])) {
           // $articles= Article::avbody()->lists('id');
           // foreach ($articles as $k => $id) {
                add_article_attribute($_GET["id"],'id','save');
            //}
        }elseif ($i=='cate'){

            $all_categories=Category::lists('id');
            foreach ($all_categories as $k=>$id){
               save_cate_cache($id);
            }
        } else if ($i=="para"){
            save_para_cache();
        } else if ($i=="tag" and Input::get('type')=="enl"){
            save_tag_cache(null,'enl');
        } else if ($i=="tag" and Input::get('type')=="dark"){
            save_tag_cache(null,'dark');
        } else if ($i=="god" and Input::get('type')=="god"){
            save_tag_cache(null,'god');
        }else {

            $articles = Article::publish()->take($loop)->skip($i * $loop)->lists('id');
            foreach ($articles as $k => $id) {

                $uid=save_article_cache($id);
                echo "文章 ".$id."(".$uid->ez_map[0]->unique_id.")<br>";
            }
        }


    }



    public function instant(Request $request, $id)
    {

        $data = Input::all();
        $article = Article::find($id);
        $data['updated_user']=Auth::user()->get()->id;
        $article->update($data);
        save_article_cache($id);
    }


    public function listData(){
    
        //return [1,2,3,4,5];

        //print_r(Config::get('app.porn_categories'));

        $ac = new \articleCollection(Config::get('app.porn_categories'));

        
        $page = \http::post('page') OR 1;
        $dir = \http::post('dir') OR 'DESC';
        $ac->page=$page;
        $ac->dir=$dir;
        
        foreach($_POST as $prop => $value){
            $ac->$prop=$value;
        }


        
        $as = $ac->query();

        $bar = $ac->pageBar();
        
        $r = [];
        $r['articles'] = $as;
        $r['pageBar']=$bar;
        return $r;
    }



    public function getList(){
            /*
            $all_categories=Category::lists('id');
            foreach ($all_categories as $k=>$id){
                $this->save_cate_cache($id);
            }*/
        return view('admin.articles.datatables_list');   
    }


    public function getIndex()
    {
        $page = ['title' => '文章管理', 'sub_title' => '',
            'url' => route('datatables.articles')];
        $categories = json_encode(Category::lists('name', 'id'));
        $users = json_encode(User::lists('name', 'id'));
        $tags=json_encode(Article::existingTags()->pluck('name')->toArray());

        $status=json_encode([""=>'ALL',"0"=>'草稿',"1"=>'上架中',"2"=>"未發佈"]);
        $pomo=json_encode(["G"=>'一般',"P"=>'情色']);
        $instant=json_encode(["0"=>'非即時',"1"=>'即時']);
        DB::table('articles')->where('status', 2)->where('publish_at', '<', Carbon::now())->where('deleted_at', null)->update(['status' => 1]);
        if(Input::has("source") ) $source=Input::get('source');else $source="enl";
        /* Article::where('publish_at','<=', Carbon::now())
             ->update(['status' => 1]);*/
        //DB::update('update articles set status=1 where publsh_at>="'.Carbon::today().'"');->where('status',2)


        // return view('admin.articles.datatables')->withPage($page);
        return view('admin.articles.datatables_filter')->withPage($page)
            ->with('tags', $tags)
            ->with('users', $users)
            ->with('categories', $categories)
            ->with('status', $status)
            ->with('instant', $instant)
        ->with('pomo', $pomo);

    }


    public function postData(Datatables $datatables)
    {
        //  $my_tags=Article::existingTags()->pluck('id','name')->all();
        //dd(implode(",",$article->tags->pluck('name')->all()));
        //$article->db_tags=implode(",",$article->tags->pluck('id','name')->all());

        $articles = Article::join('categories', 'articles.category_id', '=', 'categories.id')
            ->join('articles_map', function($join) {
                $join->on('articles.id', '=', 'articles_map.articles_id')->where('articles_map.site_id',  '=',1);

            });

        //  if (Input::get('tag'))
        $request=Input::get('columns');
        $value=$request[10]['search']['value'];
        if (!empty ($value)) $articles=$articles->withAnyTag([$value]);
        $articles= $articles  ->join('users as u1', 'articles.created_user', '=', 'u1.id')// ['created_by']
        ->join('users as u2', 'articles.updated_user', '=', 'u2.id')// ['modified_by']
        ->where('deleted_at',null)
            ->select(['articles.status', 'articles.photo','articles.flag','articles.instant', 'articles.id', 'categories.name as categories_name','articles.category_id',
                'articles.title', 'u1.name as author', 'u2.name as updater', 'articles_map.unique_id as unique_id', 'articles.publish_at', 'articles.updated_at','articles.created_at']);
        /**
         * 預設 排序 order[0][column]=1&order[0][dir]=asc
         * 因為 使用ajax sql 所以 當order[0][column]=0 (第一欄)
         * 預設根據更新日期排序
         *  $datatables = $datatables->eloquent($articles);
         */
        if ($datatables->request->order[0]['column'] == 0) {
            $articles->orderByRaw('articles.id desc');
        }
        $value='';
        if (isset($request[2]['search']['value'])) $value=$request[2]['search']['value'];
        return Datatables::of($articles)
            ->filterColumn('id', 'whereRaw', "`articles_map`.`unique_id` like ? ", ["$1"])
            ->filterColumn('categories_name', 'whereRaw', "`categories`.`name` like ? ",["$1"])
            ->filterColumn('author', 'whereRaw', "`u1`.`name` like ? ", ["$1"])

            ->filterColumn('updater', 'whereRaw', "`u2`.`name` like ? ", ["$1"])
            //->filterColumn('tags', 'whereRaw', "`u2`.`name` like ? ", ["$1"])
            ->editColumn('id', function ($article) {
                $master_url = Config::get('app.master_url');
                $site="Enl";
                $url=$master_url.'/'.$article->unique_id ."/". hyphenize($article->title);
                if (in_array($article->category_id, array_keys( cache__get('enl_categories')->toArray()))){
                    $url= route('articles.show',['id'=>$article->unique_id,'title'=>hyphenize($article->title)]);
                    $site="Enl";
                } else if (in_array($article->category_id, array_keys( cache__get('dark_categories')->toArray()))){
                    $url= route('darks.show',['id'=>$article->unique_id]);
                    $site="Dark";
                } else if (in_array($article->category_id,array_keys( cache__get('avbody_categories')->toArray()))){
                    $url= route('avbodies.show',['id'=>$article->unique_id]);
                    $site="AV";
                }
                $html='';
                $html.=  '<a href="' .$url .'" data-toggle="tooltip" target="view_'. $article->id .'" data-placement="bottom" title="#' . $article->unique_id ."\n".$article->id."\n". ' 查看 '.$site.'" rel="nofollow" > ';
                $html.= ( !empty($article->photo) ?' <img src="/focus_photos/'.$article->photo.'" style="width:52px;height:32px;" />' :  $article->unique_id );
                $html.='</a>';


                // $html.=  '<a href=' . route('articles.show',['id'=>$article->unique_id,'title'=>hyphenize($article->title)]) . ' data-toggle="tooltip" target="view_'. $article->id .'"  ';
                // $html.= 'data-placement="bottom" title="#' . $article->unique_id . ' 查看" rel="nofollow" >' . $article->unique_id . '</a>';
                // if(!empty($article->photo)) $html.=' <p><img src="/focus_photos/'.$article->photo.'" style="width:30px;height:30px;" /></p>';
                //$html.='<p>'.$article->id.'</p>';


                return $html;

            })
            ->editColumn('status', function ($article) {
                if ($article->status == 1) {
                    $status = '<span class="label label-success">上架</span>';
                } elseif($article->status==2) {
                    $status = '<span class="label label-danger">未發佈</span>';
                } else{
                    $status = '<span class="label label-warning">草稿</span>';
                }

                return $status;
            })
            ->editColumn('flag', function ($article) {
                $status='';
                if ($article->flag == 'P') {
                    $status = '<span class="label label-success bg-purple">情色</span>';
                }
                return $status;
            })

            ->editColumn('instant', function ($article) {

                if ($article->instant == '1') {
                    $instant = '<span class="label label-primary bg-navy">即時</span>';
                    $instant_feeback="0";
                    $reply="標記為「非即時」文章";
                } else {
                    $instant = '<span class="label label-default">非即時</span>';
                    $instant_feeback="1";
                    $reply="標記為「即時」文章";
                }
                if ($article->flag=='P' or in_array($article->category_id, Config::get('app.porn_categories'))) {
                    $reply = "情色 / 非即時文章";
                    $click = ' ';
                } elseif ($article->publish_at=='0000-00-00 00:00:00') {
                    $reply = "草稿文章";
                    $click = ' ';
                    if ($article->instant==1){
                        $reply = "注意:因為「草稿文章」, 目前可能在編輯中，所以不會在facebook rss裡，你可以在這邊將它轉為非即時";
                        $click=' onclick="databases_instant_confirm(this.id,'.$instant_feeback.',this.name)" ';
                    }

                } else {
                    $click=' onclick="databases_instant_confirm(this.id,'.$instant_feeback.',this.name)" ';
                }

                return  ' <a href="#" '.'"  name="'.$article->title.'" id="' . $article->id . '" new_feedback="' . $instant_feeback . '"  class="btn" rel="nofollow"  data-toggle="tooltip" '
                . 'data-placement="bottom" data-original-title="'.$reply.'" '.$click.' class="reply_confirm">'.$instant.'</a>';

            })
            ->editColumn('title', function ($article) {

                return '<a href="'.URL('admin/articles/' . $article->id . '/edit').'"  title="'.$article->unique_id."\n".$article->id.'">' .('['.$article->unique_id.'] '.$article->title)."</a>";

                if (File::exists(public_path() . '/focus_photos' . "/" . $article->photo) and !empty($article->photo)) {
                    return
                        '<a href="'.URL('admin/articles/' . $article->id . '/edit') .'" class="popoverthis" data-toggle="popover"  rel="nofollow"  '
                        . 'data-content=\'' . $article->title . '<br>' . '<img src="' . asset("focus_photos/400/" . $article->photo) . '?lastmod='.$article->updated_at.'"'
                        . ' class="img-responsive" width="300px">\' title="' . $article->categories_name . '" />' . ($article->title ? $article->title : '無標題') . '</a>';
                } else {
                    return '<a href="'.URL('admin/articles/' . $article->id . '/edit').'"  title="#' . $article->id . ' 編輯">' .($article->title ? $article->title : '無標題')."</a>";
                }

            })
            ->addColumn('tags', function ($article) {
                $tags=Article::with('tagged')->find($article->id)->tags->pluck('name')->all();
                $tag_link='';

                foreach ($tags as $k=>$tag_name){
                    if ($k%3==0)  $tag_link.= "<div style='margin-bottom: 5px'>";
                    $tag_link.='<span class="label label-default"'.$tag_name.'" >'.$tag_name.'</span>&nbsp;';
                    if ($k%3==2)  $tag_link.= "</div>";
                }
                return $tag_link;

            })
            ->addColumn('action', function ($article) {
                $master_url = Config::get('app.master_url');
                $site="Enl";
                $url=$master_url.'/'.$article->unique_id ."/". hyphenize($article->title);
                if (in_array($article->category_id, array_keys( cache__get('enl_categories')->toArray()))){
                    $url= route('articles.show',['id'=>$article->unique_id,'title'=>hyphenize($article->title)]);
                    $site="Enl";
                } else if (in_array($article->category_id, array_keys( cache__get('dark_categories')->toArray()))){
                    $url= route('darks.show',['id'=>$article->unique_id]);
                    $site="Dark";
                } else if (in_array($article->category_id,array_keys( cache__get('avbody_categories')->toArray()))){
                    $url= route('avbodies.show',['id'=>$article->unique_id]);
                    $site="AV";
                }
                $formal_url=str_replace("http://admin.",'http://',  $url);
                $tmp_url='https://developers.facebook.com/tools/debug/og/object/?q='.rawurlencode($formal_url);
                $share_url='https://www.facebook.com/sharer/sharer.php?u='.rawurlencode($formal_url)."&description=".rawurlencode($article->title);
                /*if ($article->status==1) {
                    $reply="回報bug狀態";
                    $status=0;
                } else {
                    $reply = "改回正常狀態";
                    $status=1;
                }*/
                $instant_url=route('articles.instant',$article->unique_id );
                $instant_url=str_replace("http://admin.",'http://',  $instant_url);

                // .'<a href="'.$instant_url.'" target="_instant" data-toggle="tooltip" data-placement="bottom"  class="btn btn-default btn-xs small-btn" data-original-title="miu testing" rel="nofollow" ><i class="fa fa-hourglass-1"></i></a>'

                return
                  '<a href="'.$share_url.'" target="_facebook" data-toggle="tooltip" data-placement="bottom"  class="btn btn-default btn-xs small-btn" data-original-title="立即分享" rel="nofollow" ><i class=" fa  fa-facebook"></i></a>'
                .      '<a href="'.$tmp_url.'" target="_facebook" data-toggle="tooltip" data-placement="bottom"  class="btn btn-default btn-xs small-btn" data-original-title="Facebook Debugger" rel="nofollow" ><i class=" fa   fa-bug"></i></a>'
                   .'<a href="'.$instant_url.'" target="_instant" data-toggle="tooltip" data-placement="bottom"  class="btn btn-default btn-xs small-btn" data-original-title="即時文章查看" rel="nofollow" ><i class="fa fa-hourglass-1"></i></a>'

                   . '<a href="' . URL('admin/articles/' . $article->id . '/edit') . ' " style="color:rgba(8, 4, 4, 0.99)"
                 class="btn btn-default btn-xs small-btn" rel="nofollow"  data-toggle="tooltip"  target="edit_'. $article->id .'"  data-placement="bottom" data-original-title="修改"
                ><i class=" fa  fa-pencil-square-o"></i></a>'
        
                . ' <a href="#" id="' . $article->id .'" class="btn btn-default btn-xs small-btn" rel="nofollow"  data-toggle="tooltip" '
                . 'data-placement="bottom" data-original-title="刪除" onclick="databases_delete_confirm(this.id,' . $article->unique_id . ')" class="delete_confirm">'
                . '<i class=" fa  fa-trash-o"></i></a>'
                . '<a href=' . $url. ' data-toggle="tooltip" target="view_'. $article->id .'"  '
                . 'data-placement="bottom"  class="btn btn-default btn-xs pull-left small-btn" title="#' . $article->ez_map[0]->unique_id . ' 查看'.$site.'" rel="nofollow" >' . '<i class=" fa  fa-search"></i></a>' . '</a>';

            })
            ->editColumn('publish_at', function ($article) {
                $article->publish_at = Carbon::parse($article->publish_at)->lt(Carbon::minValue()) ? "" : substr($article->publish_at, 0, 16);
                return $article->publish_at;
            })
            ->editColumn('updated_at', function ($article) {
                if ($article->updated_at=="-0001-11-30 00:00:00") {
                    return $article->created_at;
                }  else {
                    return $article->updated_at;
                }
            })
            ->make(true);


    }

    /**
     * Display a listing of the resource.
     *本前用不到了 因為datatable 用 getIndex() 與anyData()
     */
    public function json(){
        //
        $input=Input::all();
        if ($input["draw"])
            $data=[];

        $articles = Article::with('author')->with('category')->orderBy('updated_at', 'desc')->paginate(15);
        //dd( $articles);
        //dd($articles->render());
        $json=["draw"=>$articles->currentPage(),"recordsTotal"=>$articles->total(),  "recordsFiltered"=>$articles->total()];



        foreach ($articles as  $k=>$article){
            $data[]=[$k,$article->title,  $article->category['name'],$article->author['name'],$article->updater['name'],
                substr($article->publish_at,0,16),substr($article->updated_at,0,16),$article->status];

        }
        $json['data']=$data;
        return response()->json($json);
        //dd($json);
    }

    public function index()
    {
        $page = ['title' => '文章管理', 'sub_title' => '',
            'url' => route('admin.articles.index')];
        $articles = Article::with('author')->with('category')->orderBy('updated_at', 'desc')->get();
        return view('admin.articles.index')->withArticles($articles
        )->withPage($page);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function check_repeat()
    {

        $data = Input::all();
        //$gsn = Input::get('gsn_code');
        //dd($data);
        switch ($data["type"]) {
            case "create":
                $match = Article::where('title', "=", $data['title']);
                break;
            case "update":
                $match = Article::where('title', "=", $data['title'])->where('id', "!=", $data['id']);
                break;

        }

        if ($match->count() > 0) {
            return 'false';
        } else {
            return 'true';
        }
        //create validator instance

    }

    public function create()
    {
        $destinationPath = public_path() . '/focus_photos/';
        $smallPath=public_path() . '/focus_photos/200/';
        /* for($i=1;$i<=10;$i++) {
             $fileName=$i.".jpg";
             Image::make($destinationPath . $fileName)->resize('200', null, function ($constraint) {
                 $constraint->aspectRatio();
             })->save($smallPath . $fileName);
         }*/
        $page = ['title' => '文章管理', 'sub_title' => '新增文章',
            'url' => route('datatables.articles')];
        $categories = Category::lists('name', 'id');
        $data = ['publish_at' => '', 'title' => '', 'content' => '', 'category_id' => '4','summary'=>'','flag'=>'','db_tags'=>''];
        $data['created_user']=$data['updated_user']=Auth::user()->get()->id;
        $data['flag']='G';
        //記憶選單
        $my_tags=Article::existingTags()->pluck('name');
        //dd(implode(",",$article->tags->pluck('name')->all()));

        $article=Article::create($data);

        $article_map=new ArticleMap();
        $article_map->articles_id=$article->id;
        $article_map->site_id=1;

        $article_map->save();
        $article_map->unique_id=$article_map->id;//$unique_id=ArticleMap::max('unique_id')+1;
        $article_map->save();

        $article_map=new ArticleMap();
        $article_map->articles_id=$article->id;
        $article_map->site_id=6;

        $article_map->save();
        $article_map->unique_id= strtolower(str_random(10));;//$unique_id=ArticleMap::max('unique_id')+1;
        $article_map->save();
        return redirect('admin/articles/'.$article->id."/edit?do=create");
        // dd($categories);
       //return view('admin.articles.create', $data)->withPage($page)->with('categories', $categories)->with(compact('my_tags'));
    }
    private  function tag_preprocess($tags,$db){
        $tags=explode(",",$tags);
        if (is_array($tags) and !empty($tags)) {
            foreach ($tags as $k =>$v){
                if (empty($v)) unset($tags[$k]);
            }
            $db->retag($tags);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */

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
            'title'=>'required',
            // 'title' => 'required|unique:articles|max:255',
           // 'content' => 'required',
            'photo' => 'image',
            // 'publish_at' => 'required'
        );
        //
        $publish = Carbon::parse($data['publish_at']);
        $now = Carbon::now();


        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = Input::only(['category_id', 'title', 'content', 'status', 'publish_at']);
        $data['title']=title_optimize($data["title"]);

        //dd($data);
        $article = new Article();

        $article->title = $data['title'];
        $article->content = Input::get('content');
        $article->category_id = Input::get('category_id');
        $article->created_user = $article->updated_user = Auth::user()->get()->id;
        $article->publish_at = $data['publish_at'];
        if (empty($data['publish_at'])){
            $article->status = 0;
        }elseif ($now->gte($publish)) {
            $article->status = 1;
        } else {
            $article->status = 2;
        }

        if ($article->save()) {
            if (Request::hasFile('photo')) {
                //

                $file = Request::file('photo');
                $extension = strtolower($file->getClientOriginalExtension());
                $destinationPath = public_path() . '/focus_photos/';
                //  $smallPath=public_path() . '/focus_photos/200/';
                $one_Path = public_path() . '/focus_photos/100/';
                $four_Path = public_path() . '/focus_photos/400/';
                $thumbnail = ['400' => $four_Path,'100' => $one_Path];
                $do_image_path=$destinationPath . $article->photo;
                $do_image_type=File::extension($do_image_path);

                $original_photo = $destinationPath . $article->photo;
                if (File::exists($destinationPath. $article->photo) and !empty($article->photo)) {
                    File::delete($destinationPath. $article->photo);
                }
                foreach ($thumbnail as $key=>$thumbnail_path) {
                    if (File::exists($thumbnail_path . $article->photo) and !empty($article->photo)) {
                        File::delete($thumbnail_path . $article->photo);
                    }
                }
                $fileName = $article->id . '.' . $extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $do_image= Image::make($destinationPath . $fileName);
                foreach ($thumbnail as $key => $val) {
                    $do_image->resize($key, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($val . $fileName);

                }
                $do_image->destroy();
                // Storage::disk('local')->put($article->id.'.'.$extension,  File::get($file));
                $article->photo = $fileName;
                $article->save();
            }
            $article_map=new ArticleMap();
            $article_map->articles_id=$article->id;
            $article_map->site_id=1;
            $article_map->unique_id=$unique_id =ArticleMap::max('unique_id')+1;

            $article_map->save();
            if (!empty($data['tags'])) $this->tag_preprocess($data['tags'],$article);
            $message = "文章已新增";
            if (cache_has('article_'.$unique_id )) {
                cache_forget('article_'.$unique_id);
            }
            return redirect('admin/articles/datatables')->with('message', $message);
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


        //$tags=Article::existingTags()->pluck('id','name')->all();
        // dd($tags);

        //return view('create', compact('tags'));
        Article::whereIn('category_id',Config::get('app.porn_categories'))->update(array('flag' =>'P'));

        $categories = Category::lists('name', 'id');

        if (Input::has('do') and Input::get("do")=="create"){
            $sub_title="新增文章";
        } else {
            $sub_title="編輯文章";
        }
        $page = ['title' => '文章管理', 'sub_title' => $sub_title,
            'url' => route('datatables.articles')];

        $article=Article::with('tagged','ez_map')->find($id);

        
        //記憶all選單
        $my_tags=$article->existingTags()->pluck('name');
        //dd(implode(",",$article->tags->pluck('name')->all()));
        //文章選選
        $site_id=get_site_id($article->category_id);
        $article->db_tags=implode(",",$article->tags->pluck('name')->all());
        $article->publish_at= Carbon::parse($article->publish_at)->lt(Carbon::minValue()) ? "" : substr($article->publish_at,0,16);

        $map=array_column($article->ez_map->toArray(),'unique_id','site_id');
        //dd($map);
        for ($i=1;$i<=6;$i++){
            if (!isset($map[$i]))$map[$i]=$map[1];

        }
        if (!empty( $article->photo)) {
            $article->photo_url = Config::get('app.master_url') . "/focus_photos/" . $article->photo;
        }else $article->photo_url='';

        return view('admin.articles.edit',$article )->withPage($page)->withMap($map)->with('categories', $categories)->with(compact('my_tags'));
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
            //'title' => 'required|unique:articles,title,' . $id . '|max:255',
            'title'=>'required',
           // 'content' => 'required',
            'photo' => 'image',
            //'publish_at' => 'required'
        );
        //
        $publish = Carbon::parse($data['publish_at']);
        $now = Carbon::now();

        $data['title']=title_optimize($data["title"]);
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $article = Article::with('ez_map')->find($id);

        $article->title = $data['title'];
        $article->content = Input::get('content');
        $article->category_id = Input::get('category_id');
        $article->updated_user = Auth::user()->get()->id;
        $article->publish_at = $data['publish_at'];
        if (empty($data['publish_at'])){
            $article->status = 0;
        }elseif ($now->gte($publish)) {
            $article->status = 1;
        } else {
            $article->status = 2;
        }
        if (Request::hasFile('photo')) {
            //

            $file = Request::file('photo');
            $extension = strtolower($file->getClientOriginalExtension());
            $destinationPath = public_path() . '/focus_photos/';
            //  $smallPath=public_path() . '/focus_photos/200/';
            $one_Path = public_path() . '/focus_photos/100/';
            $four_Path = public_path() . '/focus_photos/400/';
            $thumbnail = [ '400' => $four_Path,'100' => $one_Path,];
            $do_image_path=$destinationPath . $article->photo;
            $do_image_type=File::extension($do_image_path);

            $original_photo = $destinationPath . $article->photo;
            if (File::exists($destinationPath. $article->photo) and !empty($article->photo)) {
                File::delete($destinationPath. $article->photo);
            }
            foreach ($thumbnail as $key=>$thumbnail_path) {
                if (File::exists($thumbnail_path . $article->photo) and !empty($article->photo)) {
                    File::delete($thumbnail_path . $article->photo);
                }
            }
            $fileName = $article->id . '.' . $extension;
            $upload_success = $file->move($destinationPath, $fileName);
            $do_image= Image::make($destinationPath . $fileName);
            foreach ($thumbnail as $key => $val) {
                $do_image->resize($key, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($val . $fileName);

            }
            $do_image->destroy();
            // Storage::disk('local')->put($article->id.'.'.$extension,  File::get($file));
            $article->photo = $fileName;
            $article->save();
            if (!empty($data['tags'])) $this->tag_preprocess($data['tags'],$article);
        }
        if ($article->save()) {
            $message = "文章已編輯";
            $unique_id=$article->ez_map[0]->unique_id;
            if (cache_has('article_'.$unique_id )) {
                cache_forget('article_'.$unique_id);
            }
            return redirect('admin/articles/datatables')->with('message', $message);
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
        $article = Article::with('author', 'ez_map', 'category', 'tagged')->find($id);
        /*
         * 10/29 frank 說要保留舊圖
        if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo)) {
            File::delete(public_path() . '/focus_photos'."/".$article->photo);
        }
        */
        if ($article){

            $article->deleted_user = Auth::user()->get()->id;
            $tmp_cache='article_map_'.$article->ez_map[0]->unique_id ;
            $tmp_cache_id='article_'.$id ;
           save_cate_cache($article->category_id);
            if ($article->tags->pluck('name')->count()>0){
                foreach ($article->tags->pluck('name')->all() as $key =>$tag_name){
                    save_tag_cache($tag_name);
                }
            }
            // dd($tmp_cache);
            if (cache_has($tmp_cache)) {
                cache_forget($tmp_cache);
            }
            if (cache_has($tmp_cache_id)) {
                cache_forget($tmp_cache_id);
            }
            $article->save();
            $article->delete();

            AvUserCollect::where('article_id',$id)->delete();
            $message = $article->title . " 文章資訊已刪除";

        } else {
            save_article_cache($id);
           Article::withTrashed()->find($id)->restore();
            $message = " 文章已復原";

        }
       // echo $message;
         //return redirect('admin/articles/datatables')->with('message', $message);
    }



    public function upload(){
        $temp = current($_FILES);
        $imageFolder = public_path() . '/uploads/';

        $imageFileType  = pathinfo($temp['name'], PATHINFO_EXTENSION);

        $filename = date('YmdHis').'-'.ff\unique_id(5).'.'.$imageFileType;
        // Accept upload if there was no origin, or if it is an accepted origin
        $filetowrite = $imageFolder . $filename;
        // $url = 'http://59.126.180.51/demo/editor/tinymce/images/' . $filename;
        //$url = asset('uploads/' . $filename);
        $url = '/uploads/'.$filename;
        //$url = asset('/uploads/' . $filename);
        move_uploaded_file($temp['tmp_name'], $filetowrite);

        // Respond to the successful upload with JSON.
        // Use a location key to specify the path to the saved image resource.
        // { location : '/your/uploaded/image/file'}
        //echo json_encode(array('location' => $url));
        return response()->json(['location' => $url]);
    }

    public function upload_KILL()
    {
        $request = Request::all();
        $file = Request::file('image');
        $destinationPath = public_path() . '/uploads/';
        $extension = Request::file('image')->getClientOriginalExtension();
        $fileName = str_random(40) . '.' . $extension;
        //$filename = $file->getClientOriginalName();
        $file->move($destinationPath, $fileName);
        echo asset('uploads/' . $fileName);
        //echo "http://localhost/ezlife/public/images/128X128.png";
        /*
        // getting all of the post data
        $file = array('image' => Request::file('image'));
        // setting up rules
        $rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($file, $rules);
        if ($validator->fails()) {
            // send back to the page with the input data and errors
            //return Redirect::to('upload')->withInput()->withErrors($validator);
           //$message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['file']['error'];
           // echo "va";
            return 'Ooops!  Your upload triggered the following error:';
        }
        else {
            // checking file is valid.
            if (Request::file('image')->isValid()) {
                $destinationPath = 'public/uploads'; // upload path
                $extension = Request::file('image')->getClientOriginalExtension(); // getting image extension
                $fileName = rand(11111,99999).'.'.$extension; // renameing image
                Request::file('image')->move($destinationPath, $fileName); // uploading file to given path
                //echo "ok";
               // dd("bb");
                return asset('uploads/'.$fileName) ;//'http://test.yourdomain.al/images/' . $filename;//change this URL
                // sending back with message
               //Session::flash('success', 'Upload successfully');
                //return Redirect::to('upload');
            }
            else {
                return "aaa";
                //echo "no";
                // sending back with error message.
              //  Session::flash('error', 'uploaded file is not valid');
              //  return Redirect::to('upload');
            }
        }*/
    }

 


    private function isTagChanged($tags, $tagged){
        $dic = [];

        
        //var_dump($tagged); 

        $c = 0;

        for($i=0, $imax=count($tagged); $i<$imax; $i++){
            $d = strtolower($tagged[$i]->tag_name);
            
            $dic[$d] = 1;
            $c++;
        }


        if($tags===''){
            $tags=[];
        }else{
            $tags = explode(',', $tags);
        }
        
        if($c !== count($tags)) return true;


        for($i=0, $imax=count($tags); $i<$imax; $i++){
            $d = strtolower($tags[$i]);
            if(!isset($dic[$d])) return true;
        }


        return false;

    }

}

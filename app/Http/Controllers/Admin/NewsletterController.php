<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Newsletter;
use App\Email;
use Auth,Input,Response,Mail;
use yajra\Datatables\Datatables;
use DB;
use Image;
class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $page = ['title' => '電子報管理', 'sub_title' => '',
            'url' => route('datatables.newsletters')];



        if(Input::has("source") ) $source=Input::get('source');else $source="enl";
        /* Newsletter::where('send_at','<=', Carbon::now())
             ->update(['status' => 1]);*/
        //DB::update('update newsletters set status=1 where publsh_at>="'.Carbon::today().'"');->where('status',2)


        // return view('admin.newsletters.datatables')->withPage($page);
        return view('admin.newsletters.datatables_filter')->withPage($page);

    }
    public function postData(Datatables $datatables)
    {


        //  if (Input::get('tag'))
        $request=Input::get('columns');
      //  $value=$request[8]['search']['value'];

        $newsletters=Newsletter::join('users as u1', 'newsletters.created_user', '=', 'u1.id')// ['created_by']
        ->join('users as u2', 'newsletters.updated_user', '=', 'u2.id')// ['modified_by']
            ->select(['newsletters.id','newsletters.title', 'u1.name as author', 'u2.name as updater', 'newsletters.send_at', 'newsletters.updated_at']);
        /**
         * 預設 排序 order[0][column]=1&order[0][dir]=asc
         * 因為 使用ajax sql 所以 當order[0][column]=0 (第一欄)
         * 預設根據更新日期排序
         *  $datatables = $datatables->eloquent($newsletters);
         */
        if ($datatables->request->order[0]['column'] == 0) {
            $newsletters->orderByRaw('newsletters.updated_at desc');
        }
       // dd($newsletters->get());
        $value='';
        if (isset($request[2]['search']['value'])) $value=$request[2]['search']['value'];
        return Datatables::of($newsletters)

            ->filterColumn('author', 'whereRaw', "`u1`.`name` like ? ", ["$1"])

            ->filterColumn('updater', 'whereRaw', "`u2`.`name` like ? ", ["$1"])
            //->filterColumn('tags', 'whereRaw', "`u2`.`name` like ? ", ["$1"])


            ->editColumn('title', function ($newsletter) {

                    return '<a href="'.URL('admin/newsletters/' . $newsletter->id . '/edit').'"  title="#' . $newsletter->id . ' 編輯">' .$newsletter->title."</a>";

            })

            ->addColumn('action', function ($newsletter) {


                return '<a href="' . URL('admin/newsletters/' . $newsletter->id . '/edit') . ' " style="color:rgba(8, 4, 4, 0.99)"
                 class="btn btn-default btn-xs small-btn" rel="nofollow"  data-toggle="tooltip" data-placement="bottom" data-original-title="修改"
                ><i class=" fa  fa-pencil-square-o"></i></a>'
                . ' <a href="#" id="' . $newsletter->id . '" class="btn btn-default btn-xs small-btn" rel="nofollow"  data-toggle="tooltip" '
                . 'data-placement="bottom" data-original-title="刪除" onclick="databases_delete_confirm(this.id)" class="delete_confirm">'
                . '<i class=" fa  fa-trash-o"></i></a>';

            })
            ->editColumn('send_at', function ($newsletter) {
                $newsletter->send_at = Carbon::parse($newsletter->send_at)->lt(Carbon::minValue()) ? "" : substr($newsletter->send_at, 0, 16);
                return $newsletter->send_at;
            })
            ->make(true);


    }
    public function index()
    {
        $page = ['title' => '電子報管理', 'sub_title' => '',
            'url' => route('admin.newsletters.index')];
        $newsletters = Newsletter::with('author')->orderBy('id', 'desc')->get();
//dd($newsletters);
        return view('admin.newsletters.index')->withNewsletters($newsletters
        )->withPage($page);
    }
    public function auto_save()
    {
        $data=Input::all();

        if (!isset($data['id']) or empty($data['id'])){
            // $newsletter = new Newsletter();
            $data['created_user']=$data['updated_user']=Auth::user()->get()->id;
            $newsletter=Newsletter::create($data);
        } else {
            $data['updated_user']=Auth::user()->get()->id;
            $newsletter= Newsletter::find($data["id"]);
            $newsletter->update($data);
       }
        return Response::json(['id'=> $newsletter->id,'url'=>route('admin.newsletters.show',$newsletter->id),
            'action_url'=>route('admin.newsletters.auto.update',$newsletter->id)]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $destinationPath = public_path() . '/focus_photos/';
        $smallPath=public_path() . '/focus_photos/200/';

        $page = ['title' => '電子報管理', 'sub_title' => '新增電子報',
            'url' => route('admin.newsletters.index')];
      //  $categories = Category::lists('name', 'id');
        $data = ['send_at' => '', 'title' => '','content'=>file_get_contents(base_path() .'/mail/sample.html')];

        return view('admin.newsletters.create', $data)->withPage($page);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)

    {   $data = Input::all();
        $newsletter=new Newsletter();
        $newsletter->title = $data['title'];
        $newsletter->content = Input::get('content');

        $newsletter->created_user =  $newsletter->updated_user = Auth::user()->get()->id;

        if ($newsletter->save()){
            $message = "訊息已新增";
            return redirect('admin/newsletters/index')->with('message', $message);
            // return Redirect('admin',$message);
        } else {
            return Redirect::back()->withInput()->withErrors('存入資料失敗！');
        }
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $page = ['title' => '電子報管理', 'sub_title' => '編輯電子報',
            'url' => route('datatables.newsletters')];

        $newsletter=Newsletter::find($id);
        // dd($newsletter);

        //文章選選

        $newsletter->send_at= Carbon::parse($newsletter->send_at)->lt(Carbon::minValue()) ? "" : substr($newsletter->send_at,0,16);


        return view('admin.newsletters.edit',$newsletter )->withPage($page);
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
        $data = Input::all();
        //dd($data);
        $newsletter = Newsletter::find($id);
        $newsletter->title = $data['title'];
        $newsletter->content = Input::get('content');
        $newsletter->updated_user = Auth::user()->get()->id;

        $mail_user=Email::where('enable','Y')->take(10)->get();
        if (isset($data['send'])) {
            $s=date("H:i:s");
            foreach ($mail_user as $i =>$receiver) {
                // for ($i=0;$i<10;$i++) {
                Mail::send( 'emails.newsletter', ['newsletter' => $newsletter,'receiver'=>$receiver,'s'=>$s], function ($m) use ($newsletter, $receiver,$i,$s) {

                //Mail::later(6, 'emails.newsletter', ['newsletter' => $newsletter,'receiver'=>$receiver,'s'=>$s], function ($m) use ($newsletter, $receiver,$i,$s) {
                    // $m->from('hello@app.com', 'Your Application');

                    if ($i % 2 == 0) {
                        $email = 'c8826209@yahoo.com.tw';
                    } else {
                        $email = "giaying0315@gmail.com";
                    }
                    $name = "abc";

                    $m->to($email, $name)->subject($newsletter->title."-".$s."");
                    //$m->to($receiver->email, $receiver->name)->subject($newsletter->title);
                });
            }
            //}
            $newsletter->send_at=Carbon::now();
            $message = "寄送文件已在佇列中";
          //  dd($message);

        } else {
            $message = "電子報已編輯";

        }

        if ( $newsletter->save()) {

            return redirect('admin/newsletters')->with('message', $message);
        } else {
            return Redirect::back()->withInput()->withErrors('失败！');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $newsletter = Newsletter::find($id);
        $newsletter ->delete();
    }
}

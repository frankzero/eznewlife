<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Log, Config;
use Validator, Cache;//很重要
use Input;   //很重要
use Redirect; //很重要
use Response;
use Auth;
use yajra\Datatables\Datatables;
class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $page = ['title' => 'LOG Report', 'sub_title' => '',
            'url' => route('admin.logs.index')];



        if(Input::has("type") ) $type=Input::get('type');else $type="";
        /* Newsletter::where('send_at','<=', Carbon::now())
             ->update(['status' => 1]);*/
        //DB::update('update logs set status=1 where publsh_at>="'.Carbon::today().'"');->where('status',2)


        // return view('admin.logs.datatables')->withPage($page);
        return view('admin.logs.datatables_filter')->withPage($page)->withType($type);

    }
    public function postData(Datatables $datatables)
    {


        //  if (Input::get('tag'))
        $request=Input::get('columns');
        //  $value=$request[8]['search']['value'];
        $data=Input::all();
        $logs = Log::select(['id','data','description','created_at','updated_at'])->
        where('type',$data['type']);

        /**
         * 預設 排序 order[0][column]=1&order[0][dir]=asc
         * 因為 使用ajax sql 所以 當order[0][column]=0 (第一欄)
         * 預設根據更新日期排序
         *  $datatables = $datatables->eloquent($logs);
         */
        if ($datatables->request->order[0]['column'] == 0) {
            $logs->orderByRaw('logs.updated_at desc');
        }
        // dd($logs->get());
        $value='';
       // dd($logs);
       //if (isset($request[2]['search']['value'])) $value=$request[2]['search']['value'];
        return Datatables::of($logs)
            ->editColumn('data', function ($log) {
                $html='';
                $html.=  '<a href="' . route('articles.show',['id'=>$log->data]) .' target="_blank">'.$log->data;
                $html.='</a>';


                // $html.=  '<a href=' . route('articles.show',['id'=>$article->unique_id,'title'=>hyphenize($article->title)]) . ' data-toggle="tooltip" target="view_'. $article->id .'"  ';
                // $html.= 'data-placement="bottom" title="#' . $article->unique_id . ' 查看" rel="nofollow" >' . $article->unique_id . '</a>';
                // if(!empty($article->photo)) $html.=' <p><img src="/focus_photos/'.$article->photo.'" style="width:30px;height:30px;" /></p>';
                //$html.='<p>'.$article->id.'</p>';


                return $html;

            })
            ->make(true);


    }
    public function index()
    {
        //
        $data = Input::all();
        $page = ['title' => 'LOG Report', 'sub_title' => '',
            'url' => route('admin.logs.index')];
        $logs = Log::where('type',$data['type'])->OrderBy("id","desc")->paginate(20);
        return view('admin.logs.index')->withLogs($logs)->withPage($page);
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

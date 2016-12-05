<?php

namespace App\Http\Controllers\Admin;

use App\AvUserCollect;
use App\AvUserDailyLog,App\AvUserRegularLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\AvUser,App\AvUserLog;
use Auth,Input,Response,Mail;
use yajra\Datatables\Datatables;
use willvincent\Rateable\Rating;
use DB;
use Image;

class AvAnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {


        $data=Input::all();
        $items = AvUser::select(DB::raw('av_users.*, count(av_users_collects.id) AS `collect_counts`'))->leftjoin('av_users_collects', 'av_users_collects.av_user_id', '=', 'av_users.id')
        ->groupBy('av_users.id');
        if (!empty($data['nick_name']))  $items = $items ->where('nick_name','like','%'.$data['nick_name'].'%');
        if (!empty($data['email']))  $items = $items ->where('nick_name','like','%'.$data['email'].'%');
        if (!empty($data['created_at']))  $items = $items ->where('av_users.created_at','like','%'.$data['created_at'].'%');
        if (!empty($data['updated_at']))  $items = $items ->where('av_users.updated_at','like','%'.$data['updated_at'].'%');
        if (!empty($data['collect_counts']))  $items = $items ->havingRaw('count(av_users_collects.id)='.$data['collect_counts'].'');

        if (!empty($data['login_counts']))  $items = $items ->where('login_counts','=',''.$data['login_counts'].'');
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
    public function manageAvUserAjax()
    {
        $page = ['title' => '用戶管理', 'sub_title' => 'AvBody列表',
            'url' => route('datatables.av_users')];
        return view('admin.av_users.index')->withPage($page);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $create = AvUser::create($request->all());
        return response()->json($create);
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
        $edit = AvUser::find($id)->update($request->all());
        return response()->json($edit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AvUser::find($id)->delete();
        return response()->json(['done']);
    }
    public function getIndex()
    {

        $page = ['title' => '用戶管理', 'sub_title' => 'AvBody列表',
            'url' => route('datatables.av_users')];


        if(Auth::user()->get()->role=="U") {
            $message = "權限不足，請洽管理員";
            return redirect('admin/articles/datatables')->with('message', $message)->with('style','warning');
        }


       // if(Input::has("source") ) $source=Input::get('source');else $source="enl";
        /* Newsletter::where('send_at','<=', Carbon::now())
             ->update(['status' => 1]);*/
        //DB::update('update newsletters set status=1 where publsh_at>="'.Carbon::today().'"');->where('status',2)
      //  dd('ab');
        // return view('admin.newsletters.datatables')->withPage($page);
        return view('admin.av_users.datatables_filter')->withPage($page);

    }
    public function postData(Datatables $datatables)
    {

        $av_users = AvUser::select(['id','name','nick_name','email', 'avatar', 'login_counts','created_at','updated_at']);

        return Datatables::of($av_users)
            ->editColumn('avatar', function ($av_user) {
                return '<img src="'.$av_user->avatar.'" width="30" height="30" title="#' . $av_user->nick_name . ' /">';
            })
            ->editColumn('email', function ($av_user) {
                return '<a href="mailto:'.$av_user->email.'" class="small" >'. $av_user->email . ' </a>';
            })           ->make(true);
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
    public function storeDaily($date){

        list($year, $month, $day) = explode('-', $date);
        $tmp = Carbon::create($year, $month, $day, 0, 0, 0);
        $from_d= Carbon::create($year, $month, $day, 0, 0, 0)->toDateTimeString();
        $to_d= Carbon::create($year, $month, $day, 23, 59, 59)->toDateTimeString();
        $record_date


            = Carbon::create($year, $month, $day, 23, 59, 59)->toDateString();
        $log=new AvUserDailyLog();
        $log->visit=AvUserLog::whereBetween('record_date', $record_date)->groupBy('av_user_id')->lists('av_user_id')->count();
        $log->refresh=AvUser::whereBetween('created_at', array($from_d, $to_d))->count();
        $log->record_date=$date;
        $refer_count=$this->calcReferCount($tmp);
        $log->refer_ad=     $refer_count["ad"] ;
        $log->refer_avbody=$refer_count["avbody"];
        $log->refer_google=$refer_count["google"];
        $log->refer_facebook=$refer_count["facebook"];
        $log->refer_other=$refer_count["other"];
        $log->save();
        return $log;

    }
    //取得新會員
    public function  getMonthRefreshCount($min,$this_year,$this_month,$add){
        for($i=$this_month;$i>$this_month-12;$i--){
            // $dt = Carbon::create(date("Y"),$i, 1, 0);
            if ($i<=0) {
                $m=$i+12;
                $y=$this_year-1;
            }else{
                $m=$i;
                $y=$this_year;
            }
            $calc= Carbon::create( $y,$m, 1, 0);
            if ($calc->month<$min->month)break;
            $from_m=$calc->startOfMonth()->toDateString();

            $to_m =$calc->endOfMonth()->toDateString();
            $k=substr($calc->toDateString(),0,7);
            //echo $from_m;
            // $fresh_count[$i]=[$from_m,$to_m];
            $db=AvUserDailyLog::whereBetween('record_date', array($from_m, $to_m))->select(DB::raw('SUM(refresh) as refresh_count'))->first();
            if ($i==date("n")){
                $fresh_count[$k]=$db["refresh_count"]+$add;
            } else {
                $fresh_count[$k]=$db["refresh_count"];
            }

        }
        $tmp_fresh=array_reverse($fresh_count);
        $fresh_count['labels']='"'.implode('月","',array_keys($tmp_fresh)).'月"';
        $fresh_count['data']=implode(',',$tmp_fresh);
        $fresh_count['name']='monthFreshChart';
        $fresh_count['backgroundColor']='rgba(255, 98, 132, 0.8)';
        $fresh_count['borderColor']='rgba(255,99,132,1)';
        $fresh_count['label']='每月新會員';
        return $fresh_count;
    }
    public function getWeekRefreshCount($t_min,$start_week,$end_week,$add){
        for($i=$start_week;$i>=$end_week;$i-=86400*7){
            // $dt = Carbon::create(date("Y"),$i, 1, 0);
            $tmp=Carbon::createFromTimestamp($i);
            $c_monday=$tmp;
            $from_w= $c_monday->toDateString();
            $c_sunday=$tmp->endOfWeek();
            $to_w = $c_sunday->toDateString();

            if ( $i<$t_min)break;

            $k=substr($from_w,0,10);

            // $fresh_count[$i]=[$from_m,$to_m];
            $db=AvUserDailyLog::whereBetween('record_date', array($from_w, $to_w))->select(DB::raw('SUM(refresh) as refresh_count'))->first();
            if ($i==$start_week){
                $fresh_count[$k]=$db["refresh_count"]+$add;
            } else {
                $fresh_count[$k]=$db["refresh_count"];
            }
        }

        // dd($fresh_count);
        $tmp_fresh=array_reverse($fresh_count);
        $fresh_count['labels']='"'.implode('","',array_keys($tmp_fresh)).'"';
        $fresh_count['data']=implode(',',$tmp_fresh);
        $fresh_count['name']='weekFreshChart';
        $fresh_count['backgroundColor']='rgba(78,151,216, 0.8)';
        $fresh_count['borderColor']='rgba(78,151,216,  1)';
        $fresh_count['label']='每週新會員';
        return $fresh_count;
    }
    /***
     * [1463932800,1472659200,1470067200]-
     **/

    public function getDayRefreshCount($t_min,$start_day,$end_day){
        $db_refresh=AvUserDailyLog::whereBetween('record_date',[date("Y-m-d",$end_day),date("Y-m-d",$start_day)])->lists('refresh','record_date')->toArray();

        $today=date("Y-m-d");
        $week= ['日', '一', '二', '三', '四', '五', '六'];
        for($i=$start_day;$i>=$end_day;$i-=86400){
            // $dt = Carbon::create(date("Y"),$i, 1, 0);
            $tmp=Carbon::createFromTimestamp($i);

            $c_date_start=$tmp;
            $calc_d= $c_date_start->toDateString();
            //起始日
            $from_d= $c_date_start->toDateTimeString();
            $from_weekday=$c_date_start->dayOfWeek;
            $c_date_end=$tmp->endOfDay();
            //結束日
            $to_d = $c_date_end->toDateTimeString();
            //var_dump($from_d,$to_d);
          // $t_min= Carbon::create( 2016,5, 23, 0)->timestamp;
            if ( $i<$t_min)break;
            $k = substr($from_d, 0, 10) . "(" . $week[$from_weekday] . ")";
            if($tmp->toDateString()==$today  ) {
               // echo  "1".$tmp->toDateString()."<br>";
                $add = $fresh_count[$k] = AvUser::whereBetween('created_at', array($from_d, $to_d))->count();
            }elseif(!array_key_exists($tmp->toDateString(),$db_refresh)){
              // echo  "2".$tmp->toDateString()."<br>";
                $store=$this->storeDaily($tmp->toDateString());
                $fresh_count[$k]=$store->refresh;
            } else{
                //echo  "3".$tmp->toDateString()."<br>";
                //  $daily_log=AvUserDailyLog::where('record_date',$calc_d)->first();

                $fresh_count[$k]= $db_refresh[date("Y-m-d",$i)];
            }



        }// dd($fresh_count);
       // var_dump($fresh_count);echo "<hr>";
       // dd($fresh_count);
        $tmp_fresh=array_reverse($fresh_count);
        $fresh_count['labels']='"'.implode('","',array_keys($tmp_fresh)).'"';
        $fresh_count['data']=implode(',',$tmp_fresh);
        $fresh_count['name']='dayFreshChart';
        $fresh_count['backgroundColor']='rgba(112,197,155, 0.8)';
        $fresh_count['borderColor']='rgba(75, 192, 192, 1)';
        $fresh_count['label']='每日新會員';
        $fresh_count['add']=$add;//增加數
        return $fresh_count;
    }
    //取得每日訪譴記錄
    public function  getMonthVisitCount($min,$this_year,$this_month){
        //SELECT *,count(1) FROM `av_users_logs` where `created_at` BETWEEN '2016-08-15 00:00:00' and '2016-08-15 23:59:59' group by av_user_id
      //  $calc_end= Carbon::create( $this_year,$this_month-12, 1, 0)->toDateString();
        $calc_start=Carbon::create( $this_year,$this_month, 1, 0)->toDateString();
        $calc_end= Carbon::create( $this_year,$this_month, 1, 0)->subMonth(11)->toDateString();
       // dd([$calc_start,$calc_end]);
        $db_visit=AvUserRegularLog::whereBetween('record_date',[$calc_end,$calc_start])->where('type','M')->lists('visit','record_date')->toArray();
//dd($db_visit);
        //var_dump($db_visit);echo "<br>";
        for($i=$this_month;$i>$this_month-12;$i--){
            // $dt = Carbon::create(date("Y"),$i, 1, 0);
            if ($i<=0) {
                $m=$i+12;
                $y=$this_year-1;
            }else{
                $m=$i;
                $y=$this_year;
            }
            $calc= Carbon::create( $y,$m, 1, 0);
            if ($calc->month<$min->month)break;
            $from_m=$calc->startOfMonth()->toDateTimeString();

            $to_m =$calc->endOfMonth()->toDateTimeString();
            $from_m_r=$calc->startOfMonth()->toDateString();

            $to_m_r =$calc->endOfMonth()->toDateString();
            $k=substr($calc->toDateString(),0,7);
            $calc_d=$calc->toDateString();
            //echo $from_m."---".$to_m.",calc_d=".$calc_d.",k=".$k."<br/>";
            //2016-08-01 00:00:00---2016-08-31 23:59:59----2016-08-31

            if ($i==date("n")){
                $visit_count[$k]=AvUserLog::whereBetween('record_date', array($from_m_r, $to_m_r))->groupBy('av_user_id')->lists('av_user_id')->count();
            }elseif(!array_key_exists(Carbon::create( $y,$m, 1, 0)->toDateString(),$db_visit)){
                $regular=new AvUserRegularLog();
                $visit=AvUserLog::whereBetween('record_date', array($from_m_r, $to_m_r))->groupBy('av_user_id')->lists('av_user_id')->count();
                $regular->record_date=Carbon::create( $y,$m, 1, 0)->toDateString();
                $regular->type="M";
                $regular->visit=$visit;
                $regular->save();
                $visit_count[$k]=$visit;
            } else {
               // echo "<b>$from_m </b>";
               // var_dump($db_visit)."-->>>".$from_m;echo "<hr>";
             //   echo "$i"."-".$db_visit[$from_m]."<br>";
                $visit_count[$k]= $db_visit[substr($from_m,0,10)];
            }
        }

        $tmp_visit=array_reverse($visit_count);
        //dd($tmp_visit);
        $visit_count['labels']='"'.implode('月","',array_keys($tmp_visit)).'月"';
        $visit_count['data']=implode(',',$tmp_visit);
        $visit_count['name']='monthVisitChart';
        $visit_count['backgroundColor']='rgba(255, 98, 132, 0.8)';
        $visit_count['borderColor']='rgba(255,99,132,1)';
        $visit_count['label']='每月來訪會員';
        //dd($visit_count);
        return $visit_count;
    }

    public function getWeekVisitCount($t_min,$start_week,$end_week,$add){

        $db_visit=AvUserRegularLog::whereBetween('record_date',[date("Y-m-d",$end_week),date("Y-m-d",$start_week)])->where('type','W')->lists('visit','record_date')->toArray();

        for($i=$start_week;$i>=$end_week;$i-=86400*7){
            // $dt = Carbon::create(date("Y"),$i, 1, 0);
            $tmp=Carbon::createFromTimestamp($i);
            $c_monday=$tmp;
            $from_w= $c_monday->toDateTimeString();
            $c_sunday=$tmp->endOfWeek();
            $to_w = $c_sunday->toDateTimeString();

            $c_monday=$tmp;
            $from_w_r= $c_monday->toDateTimeString();
            $c_sunday=$tmp->endOfWeek();
            $to_w_r = $c_sunday->toDateTimeString();
            if ( $i<$t_min)break;

            $k=substr($from_w,0,10);
           // echo $i;
            if ($i==$start_week){
                $visit_count[$k]=AvUserLog::whereBetween('record_date', array($from_w_r, $to_w_r))->groupBy('av_user_id')->lists('av_user_id')->count();
            }elseif(!array_key_exists(Carbon::createFromTimestamp($i)->toDateString(),$db_visit)){

                $regular=new AvUserRegularLog();
                $visit=AvUserLog::whereBetween('record_date', array($from_w_r, $to_w_r))->groupBy('av_user_id')->lists('av_user_id')->count();
                $regular->record_date=$tmp->toDateString();
                $regular->type="W";
                $regular->visit=$visit;
                $regular->save();
                $visit_count[$k]=$visit;
            } else {
                $visit_count[$k]= $db_visit[$k];
            }
        }

      // dd($visit_count);
        $tmp_visit=array_reverse($visit_count);
        $visit_count['labels']='"'.implode('","',array_keys($tmp_visit)).'"';
        $visit_count['data']=implode(',',$tmp_visit);
        $visit_count['name']='weekVisitChart';
        $visit_count['backgroundColor']='rgba(78,151,216, 0.8)';
        $visit_count['borderColor']='rgba(78,151,216,  1)';
        $visit_count['label']='每週來訪會員';
        return $visit_count;
    }
    public function getDayVisitCount($t_min,$start_day,$end_day){
        $today=date("Y-m-d");
        $week= ['日', '一', '二', '三', '四', '五', '六'];

        $db_visit=AvUserDailyLog::whereBetween('record_date',[date("Y-m-d",$end_day),date("Y-m-d",$start_day)])->lists('visit','record_date')->toArray();

        for($i=$start_day;$i>=$end_day;$i-=86400){
            // $dt = Carbon::create(date("Y"),$i, 1, 0);
           // echo $i."<br>";
            $tmp=Carbon::createFromTimestamp($i);
            $c_date_start=$tmp;
            $from_d= $c_date_start->toDateTimeString();
            $from_weekday=$c_date_start->dayOfWeek;
            $c_date_end=$tmp->endOfDay();
            $to_d = $c_date_end->toDateTimeString();
            $c_date_start=$tmp;
            $from_d_r= $c_date_start->toDateString();
            $from_weekday=$c_date_start->dayOfWeek;
            $c_date_end=$tmp->endOfDay();
            $to_d_r = $c_date_end->toDateString();

            $t_min= Carbon::create( 2016,5, 23, 0)->timestamp;
            if ( $i<$t_min)break;
            $calc_d=$c_date_start->toDateString();
            $k=substr($from_d,0,10)."(".$week[$from_weekday].")";
           // echo $k."<br>";
            if($tmp->toDateString()==$today or !array_key_exists($tmp->toDateString(),$db_visit) ) {
                $add=$visit_count[$k]=AvUserLog::where ('record_date',$from_d_r)->groupBy('av_user_id')->lists('av_user_id')->count();
            }elseif(!array_key_exists($tmp->toDateString(),$db_visit)){
                // echo  "2".$tmp->toDateString()."<br>";
                $store=$this->storeDaily($tmp->toDateString());
                $fresh_count[$k]=$store->visit;
            } else{
              //  $daily_log=AvUserDailyLog::where('record_date',$calc_d)->first();

                $visit_count[$k]= $db_visit[date("Y-m-d",$i)];
            }

        }

      /// dd($visit_count);
        $tmp_visit=array_reverse($visit_count);
        $visit_count['labels']='"'.implode('","',array_keys($tmp_visit)).'"';
        $visit_count['data']=implode(',',$tmp_visit);
        $visit_count['name']='dayVisitChart';
        $visit_count['backgroundColor']='rgba(112,197,155, 0.8)';
        $visit_count['borderColor']='rgba(75, 192, 192, 1)';
        $visit_count['label']='每日來訪會員';
        $visit_count['add']=$add;
        return $visit_count;
    }
    public function calcReferCount($date){
        $today=date("Y-m-d");

        $from=$date->startOfDay()->toDateTimeString();
        $from_r=$date->startOfDay()->toDateString();
        $to=$date->endOfDay()->toDateTimeString();
        $calc_d=$date->toDateString();
        $refer = DB::table("av_users_logs")
            ->select(DB::raw("min(created_at),id,av_user_id,refer"))
            ->groupBy("av_user_id")
            ->where('record_date', $from_r)
            ->havingRaw("min(created_at)")
            ->get();
        $refer_count=[];
        $refer_count["ad"]=$refer_count["avbody"]=$refer_count["google"]=$refer_count["facebook"]=$refer_count["other"]=0;
        $i=0;
        if ($refer) {
            foreach ($refer as $k => $val) {
                $v = $val->refer;
                $i++;
                // echo $v."<br/>";
                if ($v == 'http://avbody.info/rand_app') {
                    $refer_count["ad"] += 1;
                } elseif (strpos($v, 'avbody')) {
                    $refer_count["avbody"] += 1;
                } elseif (strpos($v, 'google')) {
                    $refer_count["google"] += 1;
                } elseif (strpos($v, 'facebook')) {
                    $refer_count["facebook"] += 1;
                } else {
                    $refer_count["other"] += 1;
                }
            }
        }
        return $refer_count;
    }
    public function getReferCount($date)
    {

        $today=date("Y-m-d");

        $from=$date->startOfDay()->toDateTimeString();
        $to=$date->endOfDay()->toDateTimeString();
        $calc_d=$date->toDateString();
        if( $calc_d==$today) {
            $refer_count=$this->calcReferCount($date);
        } else {
            $refer_count=AvUserDailyLog::where('record_date',$calc_d)->select('refer_other as other' ,'refer_facebook as facebook','refer_google as google','refer_avbody as avbody' ,'refer_ad as ad')->first()->toArray();

        }

//var_dump($refer->count(),$refer);

        $tmp_refer_count=$refer_count;
        $refer_count['labels']='"'.implode('","',array_keys($tmp_refer_count)).'"';
        $refer_count['data']=implode(',',$tmp_refer_count);
        $refer_count['backgroundColor']='[
                \'rgba(128, 128, 128, 0.8)\',
                \'rgba(59,89,152,0.8)\',
               \'rgba(221, 75, 57, 0.8)\',
                 \'rgba(243, 156, 18, 0.8)\',
                  \'rgba(112,201,143,0.8)\'
        ]';

        $refer_count['label']=$date->toDateString().'日 來源統計('.array_sum($tmp_refer_count).")";
        $refer_count['name']='ReferBar_'.$date->startOfDay()->timestamp;
        $refer_count['id']='refer-bar-'.$date->startOfDay()->timestamp;
        $refer_count['display']=$date->toDateString();
        $refer_count['type']='horizontalBar';
       // dd($refer_count);
        return $refer_count;

    }

    public function analysis(){
      //  dd(Input::get());
        if (Input::get('pre_save')==1){
            AvUserDailyLog::truncate();
            AvUserRegularLog::truncate();
            $dt = Carbon::yesterday();
            $start_day= $dt->startOfDay()->timestamp ;
            $t_min= Carbon::create( 2016,5, 23, 0)->timestamp;

            for($d=$start_day;$d>=$t_min;$d-=86400) {
               $this->storeDaily(date("Y-m-d",$d));
            }

            $y=2016;$min=8;
            for($m=8;$m>=8;$m--){

                $calc= Carbon::create( $y,$m, 1, 0);
                if ($calc->month<$min->month)break;
                $from_m=$calc->startOfMonth()->toDateTimeString();
                $from_m_r=$calc->startOfMonth()->toDateString();
                $to_m =$calc->endOfMonth()->toDateTimeString();
                $to_m_r=$calc->endOfMonth()->toDateString();
                $k=substr($calc->toDateString(),0,7);
                //echo $from_m;
                // $visit_count[$i]=[$from_m,$to_m];
                // echo $i;
                $record_date=$calc->startOfMonth()->toDateString();
                $regular=new AvUserRegularLog();
                $visit_count=AvUserLog::whereBetween('record_date', array($from_m_r, $to_m_r))->groupBy('av_user_id')->lists('av_user_id')->count();
                $regular->record_date=$record_date;
                $regular->type="M";
                $regular->visit=$visit_count;
                $regular->save();
            }
            $t_min= Carbon::create(2016,8, 15, 0)->timestamp;
            $start_week=Carbon::now()->startOfWeek()->subWeek(1);

            $start_week= $start_week->timestamp;
            for($i=$start_week;$i>= $t_min;$i-=86400*7){
                // $dt = Carbon::create(date("Y"),$i, 1, 0);
                $tmp=Carbon::createFromTimestamp($i);
                $c_monday=$tmp;
                $from_w= $c_monday->toDateTimeString();
                $from_w_r = $c_monday->toDateString();
                $c_sunday=$tmp->endOfWeek();
                $to_w = $c_sunday->toDateTimeString();

                $to_w_r = $c_sunday->toDateString();
                echo $i.".".date("Y-m-d",$i)."<br>";
                if ( $i<$t_min)break;
                $record_date=date("Y-m-d",$i);
                $regular=new AvUserRegularLog();
                $visit_count=AvUserLog::whereBetween('record_Date', array($from_w_r, $to_w_r))->groupBy('av_user_id')->lists('av_user_id')->count();
                $regular->record_date=$record_date;
                $regular->type="W";
                $regular->visit=$visit_count;
                $regular->save();
            }
            exit;
        }
//AvUserDailyLog::truncate();

        $page = ['title' => 'AvBody統計', 'sub_title' => 'AvBody統計',
            'url' => route('admin.newsletters.index')];
        //  $categories = Category::lists('name', 'id');
        $data = ['send_at' => '', 'title' => '','content'=>''];
        $from=Carbon::today();
        $to=Carbon::now()->endOfDay();
        /**本日來訪會員*/
    // $statistics['visit_count'] = AvUserLog::whereBetween('created_at', array($from, $to))->groupBy('av_user_id')->lists('av_user_id')->count();
        /**本日來註冊會員*/
        //$statistics['fresh_count'] = AvUser::whereBetween('created_at', array($from, $to))->count();
        /**投票數**/
        $statistics['vote_count'] = Rating::whereBetween('updated_at', array($from, $to))->count();
        /**收藏數**/
        $statistics['collect_count'] = AvUserCollect::whereBetween('created_at', array($from, $to))->count();
        /**會員人數**/
        $statistics['av_user_count'] = AvUser::count();
        /**每日新會員**/
        //每日
        $t_min= Carbon::create( 2016,5, 23, 0)->timestamp;
        $dt = Carbon::now();
        $start_day= $dt->startOfDay()->timestamp ;
        $end_day=$dt->subDays(30)->startOfDay()->timestamp;

        $fresh_count['d'] =$this->getDayRefreshCount($t_min,$start_day,$end_day);
        //dd($fresh_count);
        $tmp_fresh=array_values($fresh_count['d']);
        $statistics['fresh_count'] =$tmp_fresh[0];
        //   dd(array_sum($fresh_count['d']));
        //每月
        $this_month=$end_month=$to->month;
        $this_year=$to->year;
        $min= Carbon::create(2016,5, 22, 0);
        $fresh_count['m'] =$this->getMonthRefreshCount($min,$this_year,$this_month,$fresh_count['d']['add']);
       // dd($fresh_count);
        //每週
        $dt = Carbon::now();
        $start_week= $dt->startOfWeek()->timestamp ;
        $end_week=$dt->subWeeks(20)->startOfWeek()->timestamp;
        $fresh_count['w'] =$this->getWeekRefreshCount($t_min,$start_week,$end_week,$fresh_count['d']['add']);

        /**每日會員**/
        //每日
        $dt = Carbon::now();
        $start_day= $dt->startOfDay()->timestamp ;
        $end_day=$dt->subDays(30)->startOfDay()->timestamp;
        $visit_count['d'] =$this->getDayVisitCount($t_min,$start_day,$end_day);
        $tmp_visit=array_values($visit_count['d']);
        $statistics['visit_count'] =$tmp_visit[0];
        //每月
        $this_month=$end_month=$to->month;
        $this_year=$to->year;
        $min= Carbon::create(2016,8, 15, 0);
        $visit_count['m'] =$this->getMonthVisitCount($min,$this_year,$this_month);
        $t_min= Carbon::create( 2016,8, 15, 0)->timestamp;

        //每週
        $dt = Carbon::now();
        $start_week= $dt->startOfWeek()->timestamp ;
        $end_week=$dt->subWeeks(20)->startOfWeek()->timestamp;
        $visit_count['w'] =$this->getWeekVisitCount($t_min,$start_week,$end_week,$visit_count['d']['add']);

        /*本日來源統計*/
        $from=Carbon::yesterday();
        $refer_count=[];
        $refer_count["today"]=$this->getReferCount( Carbon::now());
        $refer_count["yesterday"]=$this->getReferCount( Carbon::yesterday());
        $refer_count["before"]=$this->getReferCount( Carbon::now()->subDays(2));

        $maxLoginCount=ceil(AvUser::max('login_counts')/5)*5;//SELECT * FROM `av_users` ORDER BY `av_users`.`login_counts` DESC
       // echo $maxLoginCount;
        for ($i=0;$i<$maxLoginCount;$i+=5){

            $from_count= $i;
            $to_count =$i+5;


            $k=$from_count."~".$to_count;
            $tmp=[$from_count,$to_count,$k];
           // var_dump($tmp);
            $frequent_count['d'][$k]=$frequent_count['b'][$k]=AvUser::whereBetween('login_counts', array($from_count, $to_count))->count();
            $tmp_rand=random_num_color();
            //$backgroundColor[]="'rgba(".$tmp_rand.",0.8)'";
           // $borderColor[]="'rgba(".$tmp_rand.",1)'";

        }
        $backgroundColor= Gradient("f39c12", "FFFFFF", ceil($maxLoginCount/5));
        $borderColor= Gradient("FFFFFF", "FFC933",ceil($maxLoginCount/5));
//dd($backgroundColor);
        $tmp_frequent=   $frequent_count['d'];
        //dd($tmp_frequent);
        $frequent_count['d']['label']='會員登入次數 環狀圖';
        $frequent_count['d']['labels']='"'.implode('次","',array_keys($tmp_frequent)).'次"';
        $frequent_count['d']['data']=implode(',',$tmp_frequent);
        $frequent_count['d']['name']='FrequentDoughnut';
        $frequent_count['d']['type']='doughnut';
        $frequent_count['d']['backgroundColor']="['#".implode("','#",$backgroundColor)."']";
        $frequent_count['d']['borderColor'] ="['#".implode("','#",$borderColor)."']";
       /* $frequent_count['d']['backgroundColor']='[
                \'rgba(255, 99, 132, 0.8)\',
                \'rgba(54, 162, 235, 0.8)\',
                \'rgba(255, 206, 86, 0.8)\',
                \'rgba(75, 192, 192, 0.8)\',
                \'rgba(153, 102, 255, 0.8)\',
                \'rgba(255, 159, 64, 0.8)\'
            ]';
        $frequent_count['d']['borderColor']='[
                \'rgba(255,99,132,1)\',
                \'rgba(54, 162, 235, 1)\',
                \'rgba(255, 206, 86, 1)\',
                \'rgba(75, 192, 192, 1)\',
                \'rgba(153, 102, 255, 1)\',
                \'rgba(255, 159, 64, 1)\'
            ]';*/
        $frequent_count['b']['labels']='會員登入次數';
       // $frequent_count['b']['labels']='"'.implode('","',array_keys($tmp_frequent)).'"';
//dd($frequent_count);
        $frequent_count['b']['data']=$tmp_frequent;
        $frequent_count['b']['label']=array_keys($tmp_frequent);
        $frequent_count['b']['name']='FrequentBar';
        $frequent_count['b']['type']='horizontalBar';
        $frequent_count['b']['backgroundColor']=$backgroundColor;
        $frequent_count['b']['borderColor']=$borderColor;
        $frequent_count['t']= AvUser::OrderBy('login_counts','desc')->take(5)->get();
       // dd( $frequent_count['t']);
     //   exit;
//dd($fresh_count_txt);
//dd($frequent_count);
      //  dd($visit_count);
        //SELECT *,count(1) FROM `av_users_logs` where `created_at` BETWEEN '2016-08-15 00:00:00' and '2016-08-15 23:59:59' group by av_user_id

        return view('admin.av_users.analysis', $data)
            ->with('frequent_count',$frequent_count)
            ->with('fresh_count',$fresh_count)
            ->with('visit_count',$visit_count)
            ->with('refer_count',$refer_count)
            ->with('statistics', $statistics)->withPage($page);
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




}

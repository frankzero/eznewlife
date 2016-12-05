<?php

namespace App\Http\Controllers\Admin;

use Request;
//use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Animation,App\User,App\AvUser;
use Auth, Input, Response, Mail, Validator;
use yajra\Datatables\Datatables;
use DB;
use Image;
use Config;
use Redirect;


class ComicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getIndex()
    {
        // dd('動圖 程式維護更新中');

        $page = ['title' => '用戶管理', 'sub_title' => 'AvBody列表',
            'url' => route('datatables.avusers')];


      //  if (Input::has("source")) $source = Input::get('source'); else $source = "enl";

    //    return view('admin.avusers.datatables_filter')->withPage($page);
          //  ->with('users', $users);

    }
    public function PostData(Datatables $datatables)
    {
        $users = AvUser::select(['id','nick_name','email', 'avatar', 'password', 'remember_token', 'login_counts','created_at','updated_at']);

        return Datatables::of($users)->make();
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

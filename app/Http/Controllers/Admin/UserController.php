<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;//use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Validator;//很重要
use Input;   //很重要
use Redirect; //很重要
use Response;
use Auth;
use Hash;
//use App\Http\Controllers\Admin\Datatable;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
//use yajra\Datatables\Datatables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     *
     */

    public function index()
    {
        if(Auth::user()->get()->role=="U") {
            $message = "權限不足，請洽管理員";
            return redirect('admin/articles/datatables')->with('message', $message)->with('style','warning');
        }
        $page = ['title' => '用戶管理', 'sub_title' => '用戶列表',
            'url' => route('admin.users.index')];
        $users= User::with('articlesCount')->with('updaterArticlesCount')->orderBy('updated_at','desc')->get();
       // dd($users);
        return view('admin.users.index')->withUsers( $users
        )->withPage($page);
        //return view('admin.users.index_list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function check_repeat()
    {

        $data = Input::all();
       // echo "aassssssssssssssssssssss";
        //$gsn = Input::get('gsn_code');
        //dd($data);
        switch ($data["type"]) {
            case "create":
                $match = User::where('name', "=", $data['name']);
                break;
            case "update":
                $match = User::where('name', "=", $data['name'])->where('id', "!=", $data['id']);
                break;
            case "create_email":
                $match = User::where('email', "=", $data['email']);
                break;
            case "update_email":
                $match = User::where('email', "=", $data['email'])->where('id', "!=", $data['id']);
                break;
            case "self_update_email":
                $match = User::where('email', "=", $data['email'])->where('id', "!=", Auth::user()->get()->id);
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
        $page = ['title' => '用戶管理', 'sub_title' => '新增用戶',
            'url' => route('admin.users.index')];

        $data=['name'=>'','email'=>'','password'=>'','role'=>'U','fb_page'=>''];
        // dd($categories);
        return view('admin.users.create',$data)->withPage($page);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = Input::all();


        $rules=[
            'name' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'fb_page'=>'numeric',
            'password' => 'required|min:6',
        ];
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'fb_page'=>$data['fb_page'],
                'role' => $data['role']
            ]);

            //echo "ok";
            $message="用戶已新增";
            return redirect('admin/users')->with('message', $message);
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
        $page = ['title' => '用戶管理', 'sub_title' => '編輯用戶',
            'url' => route('admin.users.index')];



        return view('admin.users.edit',User::find($id))->withPage($page);
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
        $data=Input::all();
        //$data = Input::only('name','email','password');

        $rules=[
            'name' => 'required|unique:users,name,' . $id . '|max:255',
            'fb_page'=>'numeric',
            'email' => 'required|email|max:255|unique:users,email,' . $id ,
            'password' => 'min:6',
        ];

        $validator = Validator::make($data, $rules);
        if (empty($data["password"])) {
            unset($data["password"]);
        } else {
            $data["password"] =  bcrypt($data['password']);
        }

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $user = User::find($id);
            $user->update($data);

            $message="用戶已修改";
            return redirect('admin/users')->with('message', $message);
        }

    }
    public function self()
    {
        $page = ['title' => '個人資料管理', 'sub_title' => '資料修改',
            'url' => route('admin.users.self')];


        return view('admin.users.self',User::find(Auth::user()->get()->id))->withPage($page);
    }
    public function self_update()
    {

        $data = Input::only(array('email','fb_page'));
        $rules = array(

            'email' => 'required|email|max:255|unique:users,email,' . Auth::user()->get()->id,
            'fb_page'=>'numeric'
        );
        $validator = Validator::make(
            $data,$rules

        );
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $user = User::find(Auth::user()->get()->id);
            $user->email=$data['email'];
            $user->fb_page=$data['fb_page'];
            $user->save();

            $message="Hi,".Auth::user()->get()->name."個人資料已修改";
            return redirect('admin/articles/datatables')->with('message', $message);
        }

        //return View::make('members.self', $member);
    }
    public function password()
    {
        $page = ['title' => '個人資料管理', 'sub_title' => '密碼修改',
            'url' => route('admin.users.self')];


       return view('admin.users.password',User::find(Auth::user()->get()->id))->withPage($page);
    }
    public function password_check()
    {

        $data = Input::all();
        //$password=Hash::make($data["password"]);
        $result=Hash::check($data['password'], Auth::user()->get()->password);
        // $match = Member::where( 'id','=',Auth::user()->get()->id)->where('password', "=",  $password.$data['password']);
        if ($result) {
            return 'true'; //找到了
        } else {
            return 'false'; //失敗，舊密碼輸入錯誤
        }
    }
    public function password_update()
    {
        $rules = array(
            'password'          => 'required',
            'new_password'              => 'min:6|different:password',
            'confirm_password' => 'required_with:new_password|min:6'
        );
        //password update.
        $data=Input::only('password', 'new_password', 'confirm_password');

        $validator = Validator::make($data, $rules);
        if ($validator->fails())
        {

            return Redirect::back()->withErrors($validator)->withInput();
        }
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $user = User::find(Auth::user()->get()->id);
            $user->password = Hash::make($data['new_password']);
            $user->save();
            Auth::logout();
            $message="個人密碼已修改，請重新登入";
            return redirect('auth/login')->with('message', $message);
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
        $user = User::find($id);

        $user->delete();
        $message = $user->name . " 資訊已刪除";
        return redirect('admin/users')->with('message',$message);
    }
}

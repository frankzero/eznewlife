<?php

namespace App\Http\Controllers\Admin;



use Illuminate\Http\Request;//use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User, App\Category, Config;
use Validator, Cache;//很重要
use Input;   //很重要
use Redirect; //很重要
use Response;
use Auth;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Input::all();
        if (Auth::user()->get()->role == "U") {
            $message = "權限不足，請洽管理員";
            return redirect('admin/articles/datatables')->with('message', $message)->with('style', 'warning');
        }
        // dd([Config::get('app.domain'),$data['domain']]);
        $page = ['title' => '文章類別管理', 'sub_title' =>  ' 類別列表',
            'url' => route('admin.categories.index')];
        $categories = Category::orderBy('id')->get();
        // dd($categories);
        return view('admin.categories.index')->withCategories($categories)->withPage($page);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Input::all();
        if (Auth::user()->get()->role == "U") {
            $message = "權限不足，請洽管理員";
            return redirect('admin/articles/datatables')->with('message', $message)->with('style', 'warning');
        }
        $page = ['title' => '文章類別管理', 'sub_title' =>  '類別新增',
            'url' => route('admin.categories.index')];

        $data = ['name' => '', 'description' => ''];

        // dd($categories);
        return view('admin.categories.create', $data)->withPage($page);
    }

    public function check_repeat()
    {

        $data = Input::all();
        // echo "aassssssssssssssssssssss";
        //$gsn = Input::get('gsn_code');
        //dd($data);
        switch ($data["type"]) {
            case "create":
                $match = Category::where('name', "=", $data['name']);
                break;
            case "update":
                $match = Category::where('name', "=", $data['name'])->where('id', "!=", $data['id']);
                break;
            case "create_email":
                $match = Category::where('email', "=", $data['email']);
                break;
            case "update_email":
                $match = Category::where('email', "=", $data['email'])->where('id', "!=", $data['id']);
                break;
            case "self_update_email":
                $match = Category::where('email', "=", $data['email'])->where('id', "!=", Auth::user()->get()->id);
                break;
        }

        if ($match->count() > 0) {
            return 'false';
        } else {
            return 'true';
        }
        //create validator instance
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = Input::all();

        if (cache_has('getez_category')) {
            cache_forget('getez_category');
        }
        $rules = [

            'name' => 'required|max:255|unique:categories,name,NULL,id'
        ];
        $validator = Validator::make($data, $rules);
        $data['created_user']=$data['updated_user']=Auth::user()->get()->id;
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            Category::create($data);
            //echo "ok";
            $message = "類別已新增";
            return redirect('admin/categories' )->with('message', $message);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);

        $page = ['title' => '文章類別管理', 'sub_title' =>  '類別編輯',
            'url' => route('admin.categories.index')];


        return view('admin.categories.edit', $category)->withPage($page);
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

        if (cache_has('getez_category')) {
            cache_forget('getez_category');
        }
        $rules = [

            'name' => 'required|max:255|unique:categories,name,' . $id . ',id'
        ];
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $data['updated_user']=Auth::user()->get()->id;

            Category::find($data["id"])
                ->update($data);
            //echo "ok";
            $message = "類別已修改";
            return redirect('admin/categories')->with('message', $message);
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


        $category =Category::find($id);

        if ($category->articles()->count()>0){
            $message = "[". $category->name ." ]，已有資料無法刪除";
        };


        $message = "[". $category->name ." ]類別已刪除";
        $category->delete();
        return redirect('admin/categories' );
    }
}

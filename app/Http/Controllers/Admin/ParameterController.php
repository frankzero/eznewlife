<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;//use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User, App\Parameter, App\Category, Config;
use Validator, Cache;//很重要
use Input;   //很重要
use Redirect; //很重要
use Response;
use Auth;


class ParameterController extends Controller
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
       // dd($list);
        //$parameter=explode(",",Parameter::where('domain','getez.info')->where('name','categories')->list("data"));
        //dd($parameter);
        // dd([Config::get('app.domain'),$data['domain']]);
        $subject = array_search($data['domain'], Config::get('app.domain'));
        $page = ['title' => '參數管理', 'sub_title' => $subject . ' 參數列表',
            'url' => route('admin.parameters.index')];
        $parameters = Parameter::where('domain', $data['domain'])->where('name', '!=', 'categories')->orderBy('name')->get();
        // dd($parameters);
        return view('admin.parameters.index')->withParameters($parameters)->withPage($page);
        //return view('admin.parameters.index_list');
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
        $subject = array_search($data['domain'], Config::get('app.domain'));
        $page = ['title' => '參數管理', 'sub_title' => $subject . '參數新增',
            'url' => route('admin.parameters.index')];

        $data = ['name' => '', 'data' => '', 'description' => '', 'domain' => $data['domain']];

        // dd($categories);
        return view('admin.parameters.create', $data)->withPage($page);
    }

    public function check_repeat()
    {

        $data = Input::all();
        // echo "aassssssssssssssssssssss";
        //$gsn = Input::get('gsn_code');
        //dd($data);
        switch ($data["type"]) {
            case "create":
                $match = Parameter::where('name', "=", $data['name'])->where('domain', "=", $data['domain']);
                break;
            case "update":
                $match = Parameter::where('name', "=", $data['name'])->where('domain', "=", $data['domain'])->where('id', "!=", $data['id']);
                break;
            case "create_email":
                $match = Parameter::where('email', "=", $data['email']);
                break;
            case "update_email":
                $match = Parameter::where('email', "=", $data['email'])->where('id', "!=", $data['id']);
                break;
            case "self_update_email":
                $match = Parameter::where('email', "=", $data['email'])->where('id', "!=", Auth::user()->get()->id);
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

        if (cache_has('getez_parameter')) {
            cache_forget('getez_parameter');
        }
        $rules = [

            'name' => 'required|max:255|unique:parameters,name,NULL,id,' . "domain," . $data["domain"]
        ];
        $validator = Validator::make($data, $rules);
        $data['created_user'] = $data['updated_user'] = Auth::user()->get()->id;
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            Parameter::create($data);
            $this->cache();
            //echo "ok";
            //$message = "參數已新增";
            return redirect('admin/parameters' . "?domain=" . $data['domain']);//->with('message', $message);
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
        $parameter = Parameter::find($id);
        $subject = array_search($parameter->domain, Config::get('app.domain'));
        $page = ['title' => '參數管理', 'sub_title' => $subject . '參數編輯',
            'url' => route('admin.parameters.index')];


        return view('admin.parameters.edit', $parameter)->withPage($page);
    }

    public function categories_edit()
    {
        $data = Input::all();
        $parameter = Parameter::where("domain", $data["domain"])->where("name", "categories")->first();
        $categories = Category::lists('name', 'id');
        //dd($categories);
        if (count($parameter)==0){
            $parameter = new Parameter();
            $parameter->domain = $data["domain"];
            $parameter->description = "網站呈現類別";
            $parameter->name = "categories";
            $parameter->created_user =Auth::user()->get()->id;
            $parameter->updated_user = Auth::user()->get()->id;
            $parameter->save();
        }

        if (!empty($parameter->data)){

            $parameter->categories_array=explode(",",$parameter->data);
        }  else {
//dd($parameter);
            $parameter->categories_array=[];
        }
        //  $parameter= $parameter->get();
        //  dd($parameter->domain);
        $subject = array_search($parameter->domain, Config::get('app.domain'));
        $page = ['title' => $subject.'網站管理', 'sub_title' => $subject. '網站類別管理',
            'url' => route('admin.parameters.index')];


        return view('admin.parameters.categories_edit', $parameter)->withCategoriesList($categories)->withPage($page);
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


        $rules = [

            'name' => 'required|max:255|unique:parameters,name,' . $id . ',id,' . "domain," . $data["domain"]
        ];
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $data['updated_user'] = Auth::user()->get()->id;

            Parameter::find($data["id"])
                ->update($data);
            $this->cache();
            //echo "ok";
           // $message = "參數已修改";
            return redirect('admin/parameters' . "?domain=" . $data['domain']);//->with('message', $message);
        }
    }

    public function categories_update(Request $request, $id)
    {
        $data = Input::all();
        $rules = [

            'categories' => 'required'
        ];
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {

            if ($request->has('categories')) {

                $data["data"] = implode(",", $data["categories"]);
            } else $data["data"] = "";
            $data['updated_user'] = Auth::user()->get()->id;

            Parameter::find($data["id"])
                ->update($data);
            //echo "ok";
            $this->cache();
            $message = "網站類別已修改";
            return redirect('admin/parameters/categories' . "?domain=" . $data['domain'])->with('message', $message);

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

        if (cache_has('getez_parameter')) {
            cache_forget('getez_parameter');
        }
        $parameter = Parameter::find($id);

        //$message = "[". $parameter->name ." ]參數已刪除";
        $parameter->delete();
        $this->cache();
        return redirect('admin/parameters' . "?domain=" . $parameter->domain);
    }
    public function cache(){

        $tmp_cache='enl_categories';
        if (cache_has( $tmp_cache)) {
            cache_forget( $tmp_cache);
        }
        $tmp_cate=Category::getList();
        cache_forever($tmp_cache,  $tmp_cate);

        $tmp_cache='getez_categories';
        if (cache_has( $tmp_cache)) {
            cache_forget( $tmp_cache);
        }
        $tmp_cate=Category::getez();
        cache_forever($tmp_cache,  $tmp_cate);


        $tmp_cache='dark_categories';
        if (cache_has( $tmp_cache)) {
            cache_forget( $tmp_cache);
        }
        $tmp_cate=Category::dark();
        cache_forever($tmp_cache,  $tmp_cate);

        $tmp_cache='avbody_categories';
        if (cache_has( $tmp_cache)) {
            cache_forget( $tmp_cache);
        }
        $tmp_cate=Category::avbody();
        cache_forever($tmp_cache,  $tmp_cate);



        $tmp_cache='enl_parameters';
        if (cache_has( $tmp_cache)) {
            cache_forget( $tmp_cache);
        }
        $tmp_cate=Parameter::enl()->lists('data', 'name');
        cache_forever($tmp_cache,  $tmp_cate);

        $tmp_cache='getez_parameters';
        if (cache_has( $tmp_cache)) {
            cache_forget( $tmp_cache);
        }
        $tmp_cate=Parameter::getez()->lists('data', 'name');
        cache_forever($tmp_cache,  $tmp_cate);


        $tmp_cache='dark_parameters';
        if (cache_has( $tmp_cache)) {
            cache_forget( $tmp_cache);
        }
        $tmp_cate=Parameter::dark()->lists('data', 'name');
        cache_forever($tmp_cache,  $tmp_cate);

        $tmp_cache='avbody_parameters';
        if (cache_has( $tmp_cache)) {
            cache_forget( $tmp_cache);
        }
        $tmp_cate=Parameter::avbody()->lists('data', 'name');
        cache_forever($tmp_cache,  $tmp_cate);

    }
}

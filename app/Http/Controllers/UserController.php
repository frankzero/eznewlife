<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class UserController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function profile(Request $request)
    {
        $page = ['title' => 'DashBoard', 'sub_title' => '',
            'url' => url('/admin')];
        return view('admin.home')->withArticles(Article::all())->withPage($page);

    }


}
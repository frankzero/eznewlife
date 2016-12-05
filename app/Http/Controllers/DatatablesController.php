<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use yajra\Datatables\Datatables;

use App\User;

class DatatablesController extends Controller
{
    public function getIndex()
    {  $page = ['title' => 'DashBoard', 'sub_title' => '',
        'url' => url('/admin')];
        return view('datatables.index')->withPage($page);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        return Datatables::of(User::select('*'))->make(true);
    }
}

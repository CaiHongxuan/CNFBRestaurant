<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * 后台首页视图
     * @return [type] [description]
     */
    public function index()
    {
    	return view('admin/home');
    }
}

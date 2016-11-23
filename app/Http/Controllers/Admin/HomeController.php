<?php

/**
 * @Author: Hongxuan
 * @Date:   2016-10-08 13:43:44
 * @Last Modified by:   Hongxuan
 * @Last Modified time: 2016-11-06 21:36:58
 */

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

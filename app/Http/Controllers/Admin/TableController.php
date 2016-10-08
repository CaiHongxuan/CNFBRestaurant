<?php

/**
 * @Author: Hongxuan
 * @Date:   2016-10-08 19:47:06
 * @Last Modified by:   Hongxuan
 * @Last Modified time: 2016-10-08 19:55:29
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\CnfbTable;

class TableController extends Controller
{
    /**
     * 餐台管理首页视图
     * @return [type] [description]
     */
    public function index()
    {
        return view('admin/table/index')->withTables(CnfbTable::all());
    }
}

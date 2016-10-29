<?php

/**
 * @Author: Hongxuan
 * @Date:   2016-10-08 19:47:06
 * @Last Modified by:   Hongxuan
 * @Last Modified time: 2016-10-24 11:32:19
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Table;

class TableController extends Controller
{
    /**
     * 餐台管理首页视图
     * @return [type] [description]
     */
    public function index()
    {
        return view('admin/table/index')->withTables(Table::all());
    }

    /**
     * 创建餐台视图
     * @return [type] [description]
     */
    public function create()
    {
        return view('admin/table/create');
    }

    /**
     * 保存餐台信息
     * @param  Request $request [description]
     * @param  [type]  $tid     [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'table_name' => 'required|unique:tables,name|max:20',
        ]);

        $Table = new Table;
        $Table->name = trim($request->get('table_name'));

        if($Table->save()){
            return redirect('admin/table');
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }

    /**
     * 编辑餐台
     * @param  [type] $tid [餐台ID]
     * @return [type]      [description]
     */
    public function edit($tid)
    {
        return view('admin/table/edit')->withTable(Table::find($tid));
    }

    /**
     * 更新餐台信息
     * @param  Request $request [description]
     * @param  [type]  $tid     [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $tid)
    {
        $this->validate($request, [
            'table_name' => 'required|unique:tables,name,'.$tid.'|max:20',
        ]);

        $Table = Table::find($tid);
        $Table->name = trim($request->get('table_name'));

        if($Table->save()){
            return redirect('admin/table');
        } else {
            return redirect()->back()->withInput()->withErrors('更新失败！');
        }
    }

    public function destroy($tid)
    {
        Table::find($tid)->delete();
        return redirect()->back()->withInput()->withErrors('删除成功！')->withSuccess('success');
    }

}

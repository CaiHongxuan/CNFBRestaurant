<?php

/**
 * @Author: Hongxuan
 * @Date:   2016-10-08 19:47:06
 * @Last Modified by:   Hongxuan
 * @Last Modified time: 2016-11-16 15:33:35
 */

namespace App\Http\Controllers\Admin;

use App\Entities\Table;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use QrCode;
use Cache;
use DB;
use Illuminate\Support\Facades\Storage;

class TableController extends Controller
{
    /**
     * 餐台管理首页视图
     * @return [type] [description]
     */
    public function index()
    {
        $tables = Cache::rememberForever('tables', function() {
            return Table::all();
        });
        return view('admin/table/index')->with('tables', $tables);
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
        Cache::forget('tables');
        $this->validate($request, [
            'table_name' => 'required|unique:tables,name|max:20',
        ]);

        $tableName = trim($request->get('table_name'));

        if ($id = DB::table('tables')->insertGetId(['name' => $tableName, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')])) {
            if(!file_exists(storage_path('app/public/qrcodes')))
                mkdir(storage_path('app/public/qrcodes'));
            QrCode::format('png')->size(300)->merge('/public/favicon.ico', .15)->margin(3)->generate(env('APP_URL') . '/table/' . $id, storage_path("app/public/qrcodes/qrcode_table" . $id . ".png"));
            return redirect('admin/table');
        } else {
            return redirect()->back()->withErrors('保存失败！');
        }
    }

    /**
     * 编辑餐台
     * @param  [type] $tid [餐台ID]
     * @return [type]      [description]
     */
    public function edit($tid)
    {
        Cache::forget('tables');
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
        Cache::forget('tables');
        $this->validate($request, [
            'table_name' => 'required|unique:tables,name,' . $tid . '|max:20',
        ]);

        $Table = Table::find($tid);
        $Table->name = trim($request->get('table_name'));

        if ($Table->save()) {
            return redirect('admin/table');
        } else {
            return redirect()->back()->withInput()->withErrors('更新失败！');
        }
    }

    public function destroy($tid)
    {
        Cache::forget('tables');
        $result = Storage::delete('public/qrcodes/qrcode_table' . $tid . '.png');
        Table::find($tid)->delete();
        return redirect()->back()->withErrors('删除成功！')->with('success', 'success');
    }

}

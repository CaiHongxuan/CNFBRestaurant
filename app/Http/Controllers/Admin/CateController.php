<?php

/**
 * @Author: Hongxuan
 * @Date:   2016-10-08 13:43:44
 * @Last Modified by:   Hongxuan
 * @Last Modified time: 2016-11-15 00:44:48
 */

namespace App\Http\Controllers\admin;

use App\Entities\Cate;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class CateController extends Controller
{
    /**
     * 菜品类别视图
     * @return [type] [description]
     */
    public function index()
    {
        $cates = DB::table('cates')->orderBy('sort', 'desc')->orderBy('created_at', 'desc')->get();
        return view('admin/cate/index')->with('cates', $cates);
        // return view('admin/cate/index')->withCates(Cate::all()->orderBy('sort', 'desc'));
    }

    /**
     * 新增菜品类别
     * @return [type] [description]
     */
    public function create()
    {
        return view('admin/cate/create');
    }

    /**
     * 保存菜品类别
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'cate_name' => 'required',
            'sort' => 'integer',
            'display' => 'integer',
        ]);

        $Cate = new Cate;
        $Cate->name = trim($request->get('cate_name'));
        $Cate->sort = $request->get('sort');
        $Cate->display = $request->get('display');

        if ($Cate->save()) {
            return redirect('admin/cate');
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }

    /**
     * 切换菜品分类的显示与否
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function toggleDisplay(Request $request)
    {
        $Cate = Cate::find($request->get('cid'));
        $Cate->display = $Cate->display ? 0 : 1;

        if ($Cate->save()) {
            return response()->json(array('msg' => '更新成功', 'show' => $Cate->display), 200);
        } else {
            return response()->json(array('msg' => '更新失败', 'show' => $Cate->display), 200);
        }
    }

    /**
     * 编辑菜品分类
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        return view('/admin/cate/edit')->withCate(Cate::find($id));
    }

    /**
     * 更新菜品分类
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'cate_name' => 'required|unique:cates,name,' . $id . '|max:20',
            'sort' => 'integer',
            'display' => 'integer',
        ]);

        $Cate = Cate::find($id);
        $Cate->name = trim($request->get('cate_name'));
        $Cate->sort = $request->get('sort');
        $Cate->display = $request->get('display');

        if ($Cate->save()) {
            return redirect('admin/cate');
        } else {
            return redirect()->back()->withInput()->withErrors('更新失败！');
        }
    }

    /**
     * 删除菜品分类
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        Cate::find($id)->delete();
        return redirect()->back()->withInput()->withErrors("删除成功！")->with('success', 'success');
    }

}

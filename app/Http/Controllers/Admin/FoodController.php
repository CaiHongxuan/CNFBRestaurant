<?php

/**
 * @Author: Hongxuan
 * @Date:   2016-10-08 13:43:44
 * @Last Modified by:   Hongxuan
 * @Last Modified time: 2016-10-08 18:47:08
 */

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\CnfbFood;
use App\CnfbCate;
use App\CnfbMedia;

class FoodController extends Controller
{
    /**
     * 菜品列表视图
     * @return [type] [description]
     */
    public function index()
    {
        $Foods = CnfbFood::all()->sortByDesc('sort');
        foreach ($Foods as $food) {
            // $arr = explode(',', $food->media_id);
            // $food->media_arr = array();
            // foreach ($arr as $v) {
            //     $food->media_arr[] = CnfbMedia::find($v)->url;
            // }
            // dd($food->media_arr);
            $food->cate = CnfbCate::find($food->cate_id)->name;
        }
        return view('admin/food/index')->withFoods($Foods);
    }

    /**
     * 新增菜品
     * @return [type] [description]
     */
    public function create()
    {
        return view('admin/food/create')->withCates(CnfbCate::where('display', '=', 1)->get(['id', 'name']));
    }

    /**
     * 保存菜品类别
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'food_name'  => 'required',
            'food_price' => 'required',
            'sort'       => 'integer',
            'rest'       => 'integer',
            'display'    => 'integer'
        ]);

        $Food = new CnfbFood;
        $Food->name        = $request->get('food_name');
        $Food->price       = $request->get('food_price');
        $Food->description = $request->get('description');
        $Food->cate_id     = $request->get('cate');
        $Food->sort        = $request->get('sort');
        $Food->rest        = $request->get('rest');
        $Food->display     = $request->get('display');

        if($Food->save()){
            return redirect('admin/food');
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
        $Food = CnfbFood::find($request->get('fid'));
        $Food->display = $Food->display ? 0 : 1;

        if($Food->save())
        {
            return response()->json(array('msg' => '更新成功', 'show' => $Food->display), 200);
        } else {
            return response()->json(array('msg' => '更新失败', 'show' => $Food->display), 200);
        }
    }

    /**
     * 编辑菜品分类
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        return view('/admin/food/edit')->withFood(CnfbFood::find($id))->withCates(CnfbCate::where('display', '=', 1)->get(['id', 'name']));
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
            'food_name'  => 'required',
            'food_price' => 'required',
            'sort'       => 'integer',
            'rest'       => 'integer',
            'display'    => 'integer'
        ]);

        $Food = CnfbFood::find($id);
        $Food->name        = $request->get('food_name');
        $Food->price       = $request->get('food_price');
        $Food->description = $request->get('description');
        $Food->cate_id     = $request->get('cate');
        $Food->sort        = $request->get('sort');
        $Food->rest        = $request->get('rest');
        $Food->display     = $request->get('display');

        if($Food->save()){
            return redirect('admin/food');
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }

    /**
     * 删除菜品分类
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        CnfbFood::find($id)->delete();
        return redirect()->back()->withInput()->withErrors("删除成功！")->withSuccess('success');
    }

}

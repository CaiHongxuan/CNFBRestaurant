<?php

/**
 * @Author: Hongxuan
 * @Date:   2016-11-15 16:20:23
 * @Last Modified by:   Hongxuan
 * @Last Modified time: 2016-11-15 23:29:53
 */

namespace App\Http\Controllers\Admin;

use App\Entities\Shop;
use App\Http\Controllers\Controller;
use Cache;
use DB;
use Storage;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * 店铺管理首页视图
     * @return [type] [description]
     */
    public function index()
    {
        Cache::forget('shops');
        $Shops = Cache::rememberForever('shops', function () {
            return DB::table('shops')->get();
        });
        return view('admin/shop/index')->with('shops', $Shops);
    }

    /**
     * 创建店铺视图
     * @return [type] [description]
     */
    public function create()
    {
        return view('admin/shop/create');
    }

    /**
     * 保存店铺信息
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        Cache::forget('shops');
        $this->validate($request, [
            'name' => 'required|unique:shops,name|max:20',
            'description' => 'max:50',
            'media' => 'required|image',
        ]);

        $data = array(
            'name' => trim($request->get('name')),
            'description' => trim($request->get('description')),
            'address' => trim($request->get('address')),
            'tel' => trim($request->get('tel')),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );

        if ($id = DB::table('shops')->insertGetId($data)) {
            $request->file('media')->storeAs('public/logos', 'shop_' . $id . '.png');
            return redirect('admin/shop');
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }

    /**
     * 编辑店铺
     * @param  [type] $sid [店铺ID]
     * @return [type]      [description]
     */
    public function edit($sid)
    {
        Cache::forget('shops');
        return view('admin/shop/edit')->with('shop', Shop::find($sid));
    }

    /**
     * 更新店铺信息
     * @param  Request $request [description]
     * @param  [type]  $sid     [店铺ID]
     * @return [type]           [description]
     */
    public function update(Request $request, $sid)
    {
        Cache::forget('shops');
        $this->validate($request, [
            'name' => 'required|max:20',
            'description' => 'max:50',
            'media' => 'image',
        ]);

        $data = array(
            'name' => trim($request->get('name')),
            'description' => trim($request->get('description')),
            'address' => trim($request->get('address')),
            'tel' => trim($request->get('tel')),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );

        // 如果有上次图片就替换之前的logo，否则还是使用之前的图片
        if ($request->hasFile('media')) {
            $request->file('media')->storeAs('public/logos', 'shop_' . $sid . '.png');
        }

        if (Shop::where('id', $sid)->update($data)) {
            return redirect('admin/shop');
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }

    /**
     * 删除店铺
     * @param  [type] $sid [店铺ID]
     * @return [type]      [description]
     */
    public function destroy($sid)
    {
        Cache::forget('shops');
        $result = Storage::delete('public/logos/shop_' . $sid . '.png');
        Shop::find($sid)->delete();
        return redirect()->back()->withErrors('删除成功！')->with('success', 'success');
    }

}

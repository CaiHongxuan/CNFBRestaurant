<?php

/**
 * @Author: Hongxuan
 * @Date:   2016-10-08 13:43:44
 * @Last Modified by:   Hongxuan
 * @Last Modified time: 2016-10-29 10:51:36
 */

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use itbdw\QiniuStorage\QiniuStorage;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Food;
use App\Cate;
use App\Media;

class FoodController extends Controller
{
    /**
     * 菜品列表视图
     * @return [type] [description]
     */
    public function index()
    {
        $Foods = Food::where('id', '>', 0)->orderBy('sort', 'desc')->orderBy('updated_at', 'desc')->get();
        $data = $Foods->toArray();
        foreach ($Foods as $key => $food) {
            if ($food->media_id) {
                $arr = explode(',', $food->media_id);
                $data[$key]['media_arr'] = array();
                foreach ($arr as $v) {
                    if (!empty($v)) {
                        $data[$key]['media_arr'][] = Media::find($v)->url;
                    }
                }
            }
            if ($food->cate_id) {
                $data[$key]['cate'] = Cate::find($food->cate_id)->name;
            }
        }

        return view('admin/food/index')->with('foods', $data);
    }

    /**
     * 新增菜品
     * @return [type] [description]
     */
    public function create()
    {
        return view('admin/food/create')->withCates(Cate::where('display', '=', 1)->get(['id', 'name']));
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

        // 若存在图片
        if ($request->hasFile('media')) {

            $file = $request->file('media');
            $file_size = round($file->getSize() / 1024);
            $file_ex = strtolower($file->getClientOriginalExtension());

            // 上传的图片不得大于2048kb，且图片应为指定格式
            if ($file_size <= 2048 && in_array($file_ex, array('jpg', 'jpeg', 'gif', 'png'))) {
                // 上传图片到七牛
                $store_name = $file->storeAs('foods', md5(date('ymdhis').$file_size).".".$file_ex, 'qiniu');

                // 将保存到七牛上的图片地址存储到系统数据库
                $disk = QiniuStorage::disk('qiniu');
                $Media = new Media;
                $data = array(
                    'url' => $disk->downloadUrl($store_name),
                    'thumbnail_url' => $disk->downloadUrl($store_name) . '?imageView2/0/w/200/h/200', // 缩略图地址
                    'type' => 'image'
                );
                if(!($media_id = $Media->create($data)->id)){
                    $disk->delete($store_name); // 保存图片地址到系统数据库失败时删除七牛上的对应图片
                    return redirect()->back()->withInput()->withErrors('保存失败！');
                }
            } else {
                return redirect()->back()->withInput()->withErrors('上传图片失败，请检查图片大小及格式是否正确！');
            }
        }

        $Food = new Food;
        $Food->name        = trim($request->get('food_name'));
        $Food->price       = $request->get('food_price');
        $Food->description = $request->get('description');
        $Food->cate_id     = $request->get('cate');
        $Food->media_id    = isset($media_id)?$media_id:null;;
        $Food->sort        = $request->get('sort');
        $Food->rest        = $request->get('rest');
        $Food->display     = $request->get('display');

        if ($Food->save()) {
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
        $Food = Food::find($request->get('fid'));
        $Food->display = $Food->display ? 0 : 1;

        if ($Food->save()) {
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
        return view('/admin/food/edit')->withFood(Food::find($id))->withCates(Cate::where('display', '=', 1)->get(['id', 'name']));
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

        // 若存在图片
        if ($request->hasFile('media')) {

            $file = $request->file('media');
            $file_size = round($file->getSize() / 1024);
            $file_ex = strtolower($file->getClientOriginalExtension());

            // 上传的图片不得大于2048kb，且图片应为指定格式
            if ($file_size <= 2048 && in_array($file_ex, array('jpg', 'jpeg', 'gif', 'png'))) {
                // 上传图片到七牛
                $store_name = $file->storeAs('foods', md5(date('ymdhis').$file_size).".".$file_ex, 'qiniu');

                // 将保存到七牛上的图片地址存储到系统数据库
                $disk = QiniuStorage::disk('qiniu');
                $Media = new Media;
                $data = array(
                    'url' => $disk->downloadUrl($store_name),
                    'thumbnail_url' => $disk->downloadUrl($store_name) . '?imageView2/0/w/200/h/200', // 缩略图地址
                    'type' => 'image'
                );
                if(!($media_id = $Media->create($data)->id)){
                    $disk->delete($store_name); // 保存图片地址到系统数据库失败时删除七牛上的对应图片
                    return redirect()->back()->withInput()->withErrors('保存失败！');
                }
            } else {
                return redirect()->back()->withInput()->withErrors('上传图片失败，请检查图片大小及格式是否正确！');
            }
        }

        $Food = Food::find($id);
        // 提取原先的图片ID，为下面从数据库和七牛删除图片做准备
        if ($Food->media_id) {
            $original_mediaId = $Food->media_id;
        }
        $Food->name        = trim($request->get('food_name'));
        $Food->price       = $request->get('food_price');
        $Food->description = $request->get('description');
        $Food->cate_id     = $request->get('cate');
        $Food->media_id    = isset($media_id)?$media_id:null;
        $Food->sort        = $request->get('sort');
        $Food->rest        = $request->get('rest');
        $Food->display     = $request->get('display');

        if ($Food->save()) {
            // 从数据库和七牛删除原图片
            if (isset($original_mediaId)) {
                $disk = QiniuStorage::disk('qiniu');
                $arr = explode(',', $original_mediaId);
                foreach ($arr as $v) {
                    if (!empty($v)) {
                        $Media = Media::find($v);
                        $disk->delete(strstr($Media->url, 'foods'));
                        $Media->delete();
                    }
                }
            }
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
        $Food = Food::find($id);
        // 删除数据库的图片记录和七牛上的图片
        if ($Food->media_id) {
            $disk = QiniuStorage::disk('qiniu');
            $arr = explode(',', $Food->media_id);
            foreach ($arr as $v) {
                if (!empty($v)) {
                    $Media = Media::find($v);
                    $disk->delete(strstr($Media->url, 'foods'));
                    $Media->delete();
                }
            }
        }
        $Food->delete();
        return redirect()->back()->withInput()->withErrors("删除成功！")->withSuccess('success');
    }

}

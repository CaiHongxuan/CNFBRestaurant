<?php

/**
 * @Author: Hongxuan
 * @Date:   2016-11-14 16:37:12
 * @Last Modified by:   Hongxuan
 * @Last Modified time: 2016-11-21 16:51:00
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entities\Admin;
use App\Entities\Media;
use Cache;
use DB;
use Auth;
use itbdw\QiniuStorage\QiniuStorage;

class AdminController extends Controller
{
    /**
     * 管理员视图
     * @return [type] [description]
     */
    public function index()
    {
        $admins = Cache::rememberForever('admins', function () {
            return DB::table('admins')
                    ->leftJoin('media', 'admins.media_id', '=', 'media.id')
                    ->select('admins.id', 'admins.name', 'admins.email', 'admins.status', 'admins.last_login_time', 'admins.created_at', 'media.url as media_url')
                    ->get();
        });
        return view('admin/admin/index', ['admins' => $admins]);
    }

    /**
     * 创建管理员视图
     * @return [type] [description]
     */
    public function create()
    {
        // 当前用户是否为超级管理员
        if (Auth::user()->status != 2) {
            return redirect()->back()->withErrors("没有权限执行该操作！");
        }
        return view('admin/admin/create');
    }

    /**
     * 控制管理员的启用与否
     * @return [type] [description]
     */
    public function toggleDisplay(Request $request)
    {
        // 当前用户是否为超级管理员
        if (Auth::user()->status != 2) {
            return response()->json('', 403);
        }

        Cache::forget('admins');
        $Admin = Admin::find($request->get('uid'));
        $Admin->status = $Admin->status ? 0 : 1;

        if ($Admin->save()) {
            return response()->json(array('msg' => '更新成功', 'show' => $Admin->status), 200);
        } else {
            return response()->json(array('msg' => '更新失败', 'show' => $Admin->status), 200);
        }
    }

    /**
     * 编辑管理员
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        // 当前用户是否为超级管理员
        if (Auth::user()->status != 2) {
            return redirect()->back()->withErrors("没有权限执行该操作！");
        }
        return view('admin/admin/edit', ['admin' => Admin::select('id', 'name',  'email', 'status', 'media_id')->find($id)]);
    }

    /**
     * 更新管理员信息
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id)
    {
        // 当前用户是否为超级管理员
        if (Auth::user()->status != 2) {
            return redirect()->back()->withErrors("没有权限执行该操作！");
        }
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|same:password',
            'display' => 'in:0,1',
        ]);
        Cache::forget('admins');

        // 若存在图片
        if ($request->hasFile('media')) {

            $file = $request->file('media');
            $file_size = round($file->getSize() / 1024);
            $file_ex = strtolower($file->getClientOriginalExtension());

            // 上传的图片不得大于2048kb，且图片应为指定格式
            if ($file_size <= 2048 && in_array($file_ex, array('jpg', 'jpeg', 'gif', 'png'))) {
                // 上传图片到七牛
                $store_name = $file->storeAs('admins', md5(date('ymdhis') . $file_size) . "." . $file_ex, 'qiniu');

                // 将保存到七牛上的图片地址存储到系统数据库
                $disk = QiniuStorage::disk('qiniu');
                $Media = new Media;
                $data = array(
                    'url' => $disk->downloadUrl($store_name),
                    'thumbnail_url' => $disk->downloadUrl($store_name) . '?imageView2/0/w/200/h/200', // 缩略图地址
                    'type' => 'image',
                );
                if (!($media_id = $Media->create($data)->id)) {
                    $disk->delete($store_name); // 保存图片地址到系统数据库失败时删除七牛上的对应图片
                    return redirect()->back()->withInput()->withErrors('保存失败！');
                }
            } else {
                return redirect()->back()->withInput()->withErrors('上传图片失败，请检查图片大小及格式是否正确！');
            }
        }

        $Admin = Admin::find($id);
        // 提取原先的图片ID，为下面从数据库和七牛删除图片做准备
        if ($Admin->media_id) {
            $original_mediaId = $Admin->media_id;
        }
        $Admin->name = $request->input('name');
        $Admin->email = $request->input('email');
        $Admin->password = bcrypt($request->input('password'));
        $Admin->media_id = isset($media_id) ? $media_id : 0;
        if ($request->input('display')) {
            $Admin->status = $request->input('display');
        }

        if ($Admin->save()) {
            // 从数据库和七牛删除原图片
            if (isset($original_mediaId)) {
                $disk = QiniuStorage::disk('qiniu');
                $Media = Media::find($original_mediaId);
                $disk->delete(strstr($Media->url, 'admins'));
                $Media->delete();
            }
            return redirect('admin/admin');
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }

    /**
     * 删除管理员
     * @return [type] [description]
     */
    public function destroy($id)
    {
        // 当前用户是否为超级管理员
        if (Auth::user()->status != 2) {
            return redirect()->back()->withErrors("没有权限执行该操作！");
        }
        Cache::forget('admins');
        $mediaId = DB::table('admins')->where('id', $id)->first()->media_id;
        // 删除数据库的图片记录和七牛上的图片
        if ($mediaId) {
            $disk = QiniuStorage::disk('qiniu');
            $Media = Media::find($mediaId);
            $disk->delete(strstr($Media->url, 'admins'));
            $Media->delete();
        }
        DB::table('admins')->where('id', $id)->delete();
        return redirect()->back()->withErrors("删除成功！")->with('success', 'success');;
    }

}

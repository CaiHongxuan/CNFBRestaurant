<?php

/**
 * @Author: Hongxuan
 * @Date:   2016-11-07 12:19:58
 * @Last Modified by:   Hongxuan
 * @Last Modified time: 2016-11-21 17:35:46
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Cache;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * 用户视图
     * @return [type] [description]
     */
    public function index()
    {
        $value = Cache::remember('users', 5, function () {
            return User::all();
        });
        return view('admin/user/index')->withUsers($value);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        Cache::forget('users');
    }

    /**
     * 切换用户是否自动晒单
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function toggleDisplay(Request $request)
    {
        Cache::forget('users');
        $User = User::find($request->get('uid'));
        $User->display = $User->display ? 0 : 1;

        if ($User->save()) {
            return response()->json(array('msg' => '更新成功', 'show' => $User->display), 200);
        } else {
            return response()->json(array('msg' => '更新失败', 'show' => $User->display), 200);
        }
    }

    public function edit($id)
    {
        Cache::forget('users');
    }

    public function update(Request $request, $id)
    {
        Cache::forget('users');
    }

    public function destroy($id)
    {
        Cache::forget('users');
    }

}

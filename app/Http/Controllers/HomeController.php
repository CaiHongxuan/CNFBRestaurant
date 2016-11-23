<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Table;
use App\Entities\Shop;
use DB;
use App\Tool\WeiXin\WXTool;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tid = 1)
    {
        // 获取用户信息
        // $userInfo = session('user');
        // if (empty($userInfo) || empty($userInfo['nickname']) || empty($userInfo['head_img_url'])) {
        //     $wx = new WXTool;
        //     $userInfo = $wx->getAccessUser();
        // }
        // $user = array(
        //     'id' => $userInfo['id'],
        //     'name' => $userInfo['nickname'],
        //     'avatar' => $userInfo['head_img_url']
        // );

        $data = DB::table('tables')
                    ->join('shops', 'tables.shop_id', '=', 'shops.id')
                    ->where('tables.id', $tid)
                    ->select('tables.id as tid', 'tables.name as title', 'shops.id', 'shops.name')
                    ->first();
        // 将$data整理成前端可以理解的数组
        $store = array(
            'id' => $data->id,
            'name' => $data->name,
            'avatar' => "/storage/logos/shop_" . $data->id . ".png",
            'table' => array(
                'id' => $data->tid,
                'title' => $data->title
            )
        );

        // $store = "{id:" . $data->id . ",name:'" . $data->name . "',avatar:'/storage/logos/shop_" . $data->id . ".png',table:{id:" . $data->tid . ", title:'" . $data->title . "'}}";
        // dd(json_encode($store));

        return view('home.index')->with('store', json_encode($store))/*->with('user', json_encode($user))*/;
    }
}

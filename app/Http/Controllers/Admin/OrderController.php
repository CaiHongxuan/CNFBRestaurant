<?php

/**
 * @Author: Hongxuan
 * @Date:   2016-10-30 16:05:11
 * @Last Modified by:   Hongxuan
 * @Last Modified time: 2016-11-10 15:25:41
 */
// 创建订单编号：date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8)

namespace App\Http\Controllers\Admin;

use App\Entities\Table;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * 订单管理首页视图
     * @return [type] [description]
     */
    public function index()
    {
        // 这里要改为视图查询
        // $Orders = DB::table('book_food')
        //             ->leftJoin('foods', 'book_food.food_id', '=', 'foods.id')
        //             ->leftJoin('media', 'foods.media_id', '=', 'media.id')
        //             ->select('book_food.book_id', 'book_food.count', 'book_food.remarks', 'foods.name', 'foods.price', 'foods.description', 'foods.rest', 'media.url', 'media.thumbnail_url')
        //             ->get()
        //             ->toArray();

        // 从数据库视图中查询所有订过的菜品信息
        $Orders = DB::table('view_orders')->get();
        // 按订单ID分组
        $data = array();
        foreach ($Orders as $key => $value) {
            $value = is_array($value) || is_object($value) ? (array) $value : $value;
            $data[$value['book_id']][] = $value;
        }

        $Books = DB::table('books')->get()->toArray();
        // 整合订单信息
        foreach ($Books as $key => $book) {
            $Books[$key] = $book = is_array($book) || is_object($book) ? (array) $book : $book;
            foreach ($data as $k => $val) {
                if ($k == $book['id']) {
                    $Books[$key]['foods'] = $val;
                    break;
                }
            }
            $Books[$key]['user'] = User::where('id', $book['user_id'])->get(['id', 'nickname'])->toArray();
            $Books[$key]['table_id'] = Table::find($book['table_id'])->name;
        }
        // var_dump($Books);die;
        return view('admin/order/index')->with('orders', $Books);
    }

    /**
     * 创建订单视图
     * @return [type] [description]
     */
    public function create()
    {
        return view('admin/table/create');
    }

    /**
     * 保存订单信息
     * @param  Request $request [description]
     * @param  [type]  $tid     [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
    }

    /**
     * 编辑餐台
     * @param  [type] $tid [餐台ID]
     * @return [type]      [description]
     */
    public function edit($tid)
    {
    }

    /**
     * 更新餐台信息
     * @param  Request $request [description]
     * @param  [type]  $tid     [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $tid)
    {
    }

    public function destroy($tid)
    {
    }

}

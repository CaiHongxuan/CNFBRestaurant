<?php

/**
 * @Author: Hongxuan
 * @Date:   2016-11-03 10:41:11
 * @Last Modified by:   Hongxuan
 * @Last Modified time: 2016-11-17 18:50:15
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Http\Request;
use DB;
use Dingo\Api\Facade\API;
use App\Entities\Food;
use App\Entities\Media;
use App\Entities\Cate;
// use Dingo\Api\Facade\Route;

class ArrayModel {

   public function toArray() {
        return $this->processArray(get_object_vars($this));
    }

    private function processArray($array) {
        foreach($array as $key => $value) {
            if (is_object($value)) {
                $array[$key] = $value->toArray();
            }
            if (is_array($value)) {
                $array[$key] = $this->processArray($value);
            }
        }
        // If the property isn't an object or array, leave it untouched
        return $array;
    }

    public function __toString() {
        return json_encode($this->toArray());
    }

}

class ApiController extends BaseController
{
    // 获取店铺信息
    // https://lab.cainiaofabu.com/vapp/dc/api/shop/{sid?}
    public function shop($sid = 1)
    {
        return DB::table('shops')->where('id', $sid)->get();
    }

    // 获取菜品信息（暂时）
    public function getFoods(Request $request)
    {
        $sid = $request->get('sid',1);
        $Foods = DB::table('foods')->where('display', 1)->where('shop_id', $sid)->select('id','cate_id as cate','name','price','media_id','description as recipe', 'taste')->orderBy('sort', 'desc')->orderBy('updated_at', 'desc')->get();
        $data = array();
        $data['code'] = 200;
        $data['data'] = array();
        foreach ($Foods as $key => $food) {
            $data['data'][$key] = array(
                'id' => $food->id,
                'cate' => $food->cate,
                'name' => $food->name,
                'price' => $food->price,
                'recipe' => $food->recipe
            );
            if ($food->media_id) {
                $arr = explode(',', $food->media_id);
                $data['data'][$key]['avatar'] = array();
                foreach ($arr as $v) {
                    if (!empty($v)) {
                        $data['data'][$key]['avatar'] = Media::find($v)->url;
                        $data['data'][$key]['avatar_min'] = Media::find($v)->url . '?imageView2/0/w/200/h/200';
                    }
                }
            }
            if ($food->taste) {
                $arr = explode(',', $food->taste);
                $data['data'][$key]['taste'] = array();
                foreach ($arr as $v) {
                    if (!empty($v)) {
                        $data['data'][$key]['taste'][] = $v;
                    }
                }
            } else {
                unset($data['data'][$key]['taste']);
            }
            // array_splice($data['data'][$key],4,1);
            // unset($data['data'][$key]['media_id']);
        }

        return json_encode($data);
    }

    // 获取菜品信息（暂时）
    public function getfs(Request $request)
    {
        $sid = $request->get('sid',1);
        $Foods = DB::table('foods')->where('display', 1)->where('shop_id', $sid)->select('id','cate_id as cate','name','price','media_id','description as recipe', 'taste')->orderBy('sort', 'desc')->orderBy('updated_at', 'desc')->get();
        $data = array();
        $data['code'] = 200;
        $data['data'] = array();
        foreach ($Foods as $key => $food) {
            $data['data'][$key] = array(
                'id' => $food->id,
                'cate' => $food->cate,
                'name' => $food->name,
                'price' => $food->price,
                'recipe' => $food->recipe
            );
            if ($food->media_id) {
                $arr = explode(',', $food->media_id);
                $data['data'][$key]['avatar'] = array();
                foreach ($arr as $v) {
                    if (!empty($v)) {
                        $data['data'][$key]['avatar'] = Media::find($v)->url;
                        $data['data'][$key]['avatar_min'] = Media::find($v)->url . '?imageView2/0/w/200/h/200';
                    }
                }
            }
            if ($food->taste) {
                $arr = explode(',', $food->taste);
                $data['data'][$key]['taste'] = array();
                foreach ($arr as $v) {
                    if (!empty($v)) {
                        $data['data'][$key]['taste'][] = $v;
                    }
                }
            } else {
                unset($data['data'][$key]['taste']);
            }
            // array_splice($data['data'][$key],4,1);
        }
        // var_export((Object)$data,true);
        // $model = new ArrayModel;
        // $test = $model->toArray((Object)$data);
        // return json_encode($test);

        return json_encode ( $data );
    }
    // 获取所有分类（暂时）
    public function getCates(Request $request)
    {
        $sid = $request->get('sid');
        $data['code'] = 200;
        $data['data'] = array();
        if ($sid) {
            $cates = DB::table('cates')->where('shop_id', $sid)->select('id', 'name as title')->get();
        } else {
            $cates = DB::table('cates')->select('id', 'name as title')->get();
        }
        foreach ($cates as $key => $cate) {
            $data['data'][] = $cate;
        }
        return json_encode($data);
    }

    public function object_to_array($obj){
        $_arr = is_object($obj)? get_object_vars($obj) :$obj;

        foreach ($_arr as $key => $val){
            $val=(is_array($val)) || is_object($val) ? $this->object_to_array($val) :$val;
            $arr[$key] = $val;
        }
        return $arr;
    }

    // 餐台二维码
    // https://lab.cainiaofabu.com/vapp/dc/api/table?id=md5(ID)

    // 获取菜品信息
    // https://lab.cainiaofabu.com/vapp/dc/api/foods?page=PAGE&per_page=COUNT

    // 获取菜品分类
    // https://lab.cainiaofabu.com/vapp/dc/api/foods/cates

    // 获取某类别的菜品列表
    // https://lab.cainiaofabu.com/vapp/dc/api/foods?cate=CATE

    // 获取菜品详情
    // https://lab.cainiaofabu.com/vapp/dc/api/foods?id=ID

    /**
     * 管理员才有权操作的接口
     */
    // 添加菜品
    // 修改菜品
    // 删除菜品

    // 添加菜品分类
    // 修改菜品分类
    // 删除菜品分类

    // 添加餐台
    // 修改餐台
    // 删除餐台

    // 查看订单
    // 修改订单
    // 删除订单

    // 查看用户信息

    // 数据统计（待定）
}

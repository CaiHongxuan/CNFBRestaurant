<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\CnfbCate;

class CateController extends Controller
{
	/**
	 * 菜品类别视图
	 * @return [type] [description]
	 */
    public function index()
    {
    	return view('admin/cate/index')->withCates(CnfbCate::all());
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
        // var_dump($request);die;
        $this->validate($request, [
            'cate_name' => 'required',
            'sort'      => 'integer',
            'display'   => 'integer'
        ]);

        $Cate = new CnfbCate;
        $Cate->name    = $request->get('cate_name');
        $Cate->sort    = $request->get('sort');
        $Cate->display = $request->get('display');

        if($Cate->save()){
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
        return response()->json(array('msg'=> '返回成功'), 200);
    }

}

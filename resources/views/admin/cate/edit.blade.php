@extends('layouts.app')

@section('sub-css')
    <link rel="stylesheet" type="text/css" href="/css/style.css">
@endsection

@section('content')
<!-- 当前位置 -->
<div id="urHere"><a href="/admin">管理首页</a><b>></b><strong>菜品分类</strong> </div>
<div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="/admin/cate" class="actionBtn">菜品分类</a>编辑分类</h3>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>
                提示!
            </h4> <strong>新增失败：输入不符合要求</strong> {!! implode('<br>', $errors->all()) !!}
        </div>
    @endif

    <form action="{{url('admin/cate/' . $cate->id)}}" method="post">
        {{ method_field('PATCH') }}
        {!! csrf_field() !!}
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
            <tr>
                <td width="80" align="right">分类名称</td>
                <td>
                    <input type="text" name="cate_name" value="{{$cate->name}}" size="40" class="inpMain" />
                </td>
            </tr>
            <tr>
                <td align="right">排序</td>
                <td>
                    <input type="text" name="sort" value="{{$cate->sort}}" size="5" class="inpMain" />
                </td>
            </tr>
            <tr>
                <td align="right">是否显示</td>
                <td>
                    <input name="display" type="radio" value="1" @if($cate->display==1) checked="checked" @endif>是&nbsp;
                    <input name="display" type="radio" value="0" @if($cate->display==0) checked="checked" @endif>否
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input name="submit" class="btn" type="submit" value="提交" />
                </td>
            </tr>
        </table>
    </form>
</div>
@endsection

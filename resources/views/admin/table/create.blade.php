@extends('layouts.app')

@section('sub-css')
    <link rel="stylesheet" type="text/css" href="/css/style.css">
@endsection

@section('content')
<!-- 当前位置 -->
<div id="urHere"><a href="/admin">管理首页</a><b>></b><strong>餐台管理</strong> </div>
<div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="/admin/table" class="actionBtn">餐台管理</a>新增餐台</h3>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>
                提示!
            </h4> <strong>新增失败：输入不符合要求</strong> <br>{!! implode('<br>', $errors->all()) !!}
        </div>
    @endif

    <form action="{{url('admin/table')}}" method="post">
        {{ csrf_field() }}
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
            <tr>
                <td width="80" align="right">餐台名称</td>
                <td>
                    <input type="text" name="table_name" value="" size="40" class="inpMain" />
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

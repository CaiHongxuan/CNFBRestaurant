@extends('layouts.app')

@section('sub-css')
    <link rel="stylesheet" type="text/css" href="/css/style.css">
@endsection

@section('content')
<!-- 当前位置 -->
<div id="urHere"><a href="/admin">管理首页</a><b>></b><strong>店铺管理</strong> </div>
<div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="/admin/shop" class="actionBtn">店铺管理</a>新增店铺</h3>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>
                提示!
            </h4> <strong>新增失败：输入不符合要求</strong> <br>{!! implode('<br>', $errors->all()) !!}
        </div>
    @endif

    <form action="{{url('admin/shop')}}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
            <tr>
                <td width="80" align="right">店铺名称</td>
                <td>
                    <input type="text" name="name" value="" size="40" class="inpMain" required />
                </td>
            </tr>
            <tr>
                <td width="80" align="right">描述</td>
                <td>
                    <textarea name="description" cols="83" rows="3" class="textArea"></textarea>
                </td>
            </tr>
            <tr>
                <td align="right">Logo</td>
                <td>
                    <input type="file" name="media" value="0" size="5" class="inpMain" />
                    <span class="cue">请上传png图片</span>
                </td>
            </tr>
            <tr>
                <td align="right">联系方式</td>
                <td>
                    <input type="tel" name="tel" value="" size="40" class="inpMain" />
                </td>
            </tr>
            <tr>
                <td align="right">地址</td>
                <td>
                    <input type="text" name="address" value="" size="40" class="inpMain" />
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

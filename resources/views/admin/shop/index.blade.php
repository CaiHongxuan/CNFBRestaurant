@extends('layouts.app')

@section('sub-css')
    <link rel="stylesheet" type="text/css" href="/css/style.css">
@endsection

@section('content')
<!-- 当前位置 -->
<div id="urHere"><a href="/admin">管理首页</a><b>></b><strong>店铺管理</strong> </div>
<div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="/admin/shop/create" class="actionBtn add">添加店铺</a>店铺管理</h3>

    @if (count($errors) > 0)
        <div class="alert alert-{{ session('success') ? session('success') : 'danger' }}">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>提示!</h4>
            {!! implode('<br>', $errors->all()) !!}
        </div>
    @endif

    <!-- 用来显示二维码 -->
    <div id="Layer" style="display: none; position: absolute; z-index: 100;"></div>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
        <tr>
            <th width="30" align="left">ID</th>
            <th width="50" align="center">Logo</th>
            <th width="80" align="center">店铺名称</th>
            <th width="100" align="center">描述</th>
            <th width="80" align="center">联系方式</th>
            <th width="80" align="center">地址</th>
            <th width="60" align="center">创建时间</th>
            <th width="80" align="center">操作</th>
        </tr>
        @foreach ($shops as $shop)
        <tr>
            <td align="left">{{ $shop->id }}</td>
            <td align="center">
                <img class="head_img" src="/storage/logos/shop_{{ $shop->id }}.png" />
            </td>
            <td align="center">{{ $shop->name }}</td>
            <td align="center">{{ $shop->description }}</td>
            <td align="center">{{ $shop->tel or ''}}</td>
            <td align="center">{{ $shop->address or '' }}</td>
            <td align="center">{{ $shop->created_at }}</td>
            <td align="center">
                <form action="{{url('admin/shop/'.$shop->id)}}" method="POST">
                    <a href="{{url('admin/shop/'.$shop->id.'/edit')}}">编辑</a> |
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <button type="submit" class="del" onclick="javascript:if(confirm('确定删除？')){return true;}else{return false;}">删除</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection

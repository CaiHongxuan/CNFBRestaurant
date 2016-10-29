@extends('layouts.app')

@section('sub-css')
    <link rel="stylesheet" type="text/css" href="/css/style.css">
@endsection

@section('content')
<!-- 当前位置 -->
<div id="urHere"><a href="/admin">管理首页</a><b>></b><strong>餐台管理</strong> </div>
<div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="/admin/table/create" class="actionBtn add">添加餐台</a>餐台管理</h3>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>提示!</h4>
            {!! implode('<br>', $errors->all()) !!}
        </div>
    @endif

    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
        <tr>
            <th width="30" align="left">ID</th>
            <th width="120" align="center">餐台名称</th>
            <th width="60" align="center">更新时间</th>
            <th width="80" align="center">操作</th>
        </tr>
        @foreach ($tables as $table)
        <tr>
            <td align="left">{{ $table->id }}</td>
            <td align="center">{{ $table->name }}</td>
            <td align="center">{{ $table->updated_at }}</td>
            <td align="center">
                <form action="{{url('admin/table/'.$table->id)}}" method="POST">
                    <a href="{{url('admin/table/'.$table->id.'/edit')}}">编辑</a> |
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <button type="submit" class="del">删除</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection

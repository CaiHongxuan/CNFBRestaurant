@extends('layouts.app')

@section('sub-css')
    <link rel="stylesheet" type="text/css" href="/css/style.css">
@endsection

@section('content')
<!-- 当前位置 -->
<div id="urHere"><a href="/admin">管理首页</a><b>></b><strong>菜品分类</strong> </div>
<div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="/admin/cate/create" class="actionBtn add">添加分类</a>菜品分类</h3>


    @if (count($errors) > 0)
        <div class="alert alert-{{ session('success') ? session('success') : 'danger' }}">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>提示!</h4>
            {!! implode('<br>', $errors->all()) !!}
        </div>
    @endif

    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
        <tr>
            <th width="40" align="left">分类ID</th>
            <th width="120" align="left">分类名称</th>
            <th width="60" align="center">排序</th>
            <th width="60" align="center">是否显示</th>
            <th width="60" align="center">创建时间</th>
            <th width="80" align="center">操作</th>
        </tr>
        @foreach ($cates as $cate)
        <tr>
            <td align="left">{{ $cate->id }}</td>
            <td align="left">{{ $cate->name }}</td>
            <td align="center">{{ $cate->sort }}</td>
            <td align="center">
                <label class="show">
                    @if ($cate->display == 1)
                        是
                    @else
                        否
                    @endif
                </label>
                &nbsp;&nbsp;<label class="changeShow" onclick="toggleShow(this, {{$cate->id}})">切换</label>
            </td>
            <td align="center">{{ $cate->created_at }}</td>
            <td align="center">
                <form action="{{url('admin/cate/'.$cate->id)}}" method="POST">
                    <a href="{{url('admin/cate/'.$cate->id.'/edit')}}">编辑</a> |
                    {{method_field('DELETE')}}
                    {{csrf_field()}}
                    <button type="submit" class="del">删除</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection

@section('sub-js')
<script type="text/javascript">
    /**
     * 控制菜品分类的显示与否
     * @param  {[type]} ele [description]
     * @param  {[type]} id  [该菜品分类的ID]
     * @return {[type]}     [description]
     */
    function toggleShow(ele, id){
        htmlobj = $.ajax({
            type: "POST",
            dataType: "json",
            data: {cid:id, _token:"{{csrf_token()}}"},
            url:"{{url('admin/cate/toggleDisplay')}}",
            success: function(data){
                if(data.show){
                    $(ele).prev().html('是');
                } else {
                    $(ele).prev().html('否');
                }
            },
            error: function(){
                console.log('ajax请求失败！');
            }
        });
    };
</script>
@endsection

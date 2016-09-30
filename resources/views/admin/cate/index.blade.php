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
        <div class="alert alert-danger">
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
                &nbsp;&nbsp;<label class="changeShow" onclick="toggleShow(this, {{$cate->id}}, {{$cate->display}})">切换</label>
            </td>
            <td align="center"><a href="/admin/cate/edit">编辑</a> | <a href="/admin/cate/destory">删除</a></td>
        </tr>
        @endforeach
    </table>
</div>
@endsection

@section('sub-js')
<script type="text/javascript">
    function toggleShow(ele, id, display){
        htmlobj = $.ajax({
            type: "POST",
            dataType: "json",
            data: {cid:id, show:display, _token:"{{csrf_token()}}"},
            url:"{{url('admin/cate/toggleDisplay')}}",
            success: function(data){
                $(ele).prev().html(data);
                // $(this).prev().html(data);
            },
            error: function(){
                console.log('ajax请求失败！');
            }
        });
    };
    // setTimeout($(".alert-danger").hide(),5000);

    // $(".changeShow").click(function(){
    //
    //     // $("#myDiv").html(htmlobj.responseText);
    // });
</script>
@endsection

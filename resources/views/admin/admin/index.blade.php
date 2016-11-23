@extends('layouts.app')

@section('sub-css')
    <link rel="stylesheet" type="text/css" href="/css/style.css">
@endsection

@section('content')
<!-- 当前位置 -->
<div id="urHere"><a href="/admin">管理首页</a><b>></b><strong>顾客管理</strong> </div>
<div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="/admin/admin/create" class="actionBtn add">添加管理员</a>管理员列表</h3>

    @if (count($errors) > 0)
        <div class="alert alert-{{ session('success') ? session('success') : 'danger' }}">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>提示!</h4>
            {!! implode('<br>', $errors->all()) !!}
        </div>
    @endif

    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
        <tr>
            <th width="50" align="center">编号{{$success or ''}}</th>
            <th width="50" align="center">头像</th>
            <th width="60" align="center">名称</th>
            <th width="60" align="center">邮箱</th>
            <th width="80" align="center">角色</th>
            <th width="80" align="center">上次登录时间</th>
            <th width="80" align="center">创建时间</th>
            <th width="80" align="center">操作</th>
        </tr>
        @foreach ($admins as $admin)
        <tr>
            <td align="center">{{ $admin->id }}</td>
            <td align="center">
                @if (isset($admin->media_url))
                    <img src="{{ $admin->media_url }}" class="head_img">
                @endif
            </td>
            <td align="center">{{ $admin->name }}</td>
            <td align="center">{{ $admin->email }}</td>
            <td align="center">
                @if ($admin->status == 2)
                    <label class="show">
                        超级管理员
                    </label>
                @elseif ($admin->status == 1)
                    <label class="show">
                        管理员
                    </label>&nbsp;
                    <label class="changeShow" onclick="toggleShow(this, {{ $admin->id }})">禁用</label>
                @else ($admin->status == 0)
                    <label class="show">
                        禁用
                    </label>&nbsp;
                    <label class="changeShow" onclick="toggleShow(this, {{ $admin->id }})">启用</label>
                @endif
            </td>
            <td align="center">{{ $admin->last_login_time or '' }}</td>
            <td align="center">{{ $admin->created_at }}</td>
            <td align="center">
                <form action="{{url('admin/admin/'.$admin->id)}}" method="POST">
                    <a href="{{url('admin/admin/'.$admin->id.'/edit')}}">编辑</a> |
                    {{method_field('DELETE')}}
                    {{csrf_field()}}
                    <button type="submit" class="del" onclick="javascript:if(confirm('确定删除？')){return true;}else{return false;}">删除</button>
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
     * 控制管理员的启用与否
     * @param  {[type]} ele [description]
     * @param  {[type]} id  [该菜品的ID]
     * @return {[type]}     [description]
     */
    function toggleShow(ele, id){
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {uid:id, _token:"{{csrf_token()}}"},
            url:"{{url('admin/admin/toggleDisplay')}}",
            success: function(data){
                if(data.show){
                    $(ele).html('禁用');
                    $(ele).prev().html('管理员');
                } else {
                    $(ele).html('启用');
                    $(ele).prev().html('禁用');
                }
            },
            error: function(){
                console.log('ajax请求失败！');
            }
        });
    };
</script>
@endsection

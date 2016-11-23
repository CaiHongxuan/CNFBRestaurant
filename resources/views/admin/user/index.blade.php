@extends('layouts.app')

@section('sub-css')
    <link rel="stylesheet" type="text/css" href="/css/style.css">
@endsection

@section('content')
<!-- 当前位置 -->
<div id="urHere"><a href="/admin">管理首页</a><b>></b><strong>顾客管理</strong> </div>
<div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="/admin/user/create" class="actionBtn add">添加顾客</a>顾客列表</h3>

    @if (count($errors) > 0)
        <div class="alert alert-{{ session('success') ? session('success') : 'danger' }}">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>提示!</h4>
            {!! implode('<br>', $errors->all()) !!}
        </div>
    @endif

    <div class="filter">
        <form action="article.php" method="post">
            <select name="cat_id">
                <option value="" selected>全部</option>
                <option value="0">推荐</option>
                <option value="1">川菜</option>
                <option value="2">粤菜</option>
            </select>
            <input name="keyword" type="text" class="inpMain" value="" size="20" />
            <input name="submit" class="btnGray" type="submit" value="筛选" />
        </form>
    </div>

    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
        <tr>
            <th width="50" align="center">编号</th>
            <th width="60" align="center">昵称</th>
            <th width="60" align="center">头像</th>
            <th align="center">个性签名</th>
            <th width="60" align="center">性别</th>
            <th width="60" align="center">年龄</th>
            <th width="80" align="center">所在地</th>
            <th width="80" align="center">是否自动晒单</th>
            <th width="80" align="center">创建时间</th>
            <th width="80" align="center">操作</th>
        </tr>
        @foreach ($users as $user)
        <tr>
            <td align="center">{{ $user->id }}</td>
            <td align="center">{{ $user->nickname }}</td>
            <td align="center">
                @if (isset($user->head_img_url))
                    <img src="{{ $user->head_img_url }}" class="head_img">
                @endif
            </td>
            <td align="center">{{ $user->description }}</td>
            <td align="center">
                @if ($user->sex == 0)
                    保密
                @elseif ($user->sex == 1)
                    男
                @else ($user->sex == 2)
                    女
                @endif
            </td>
            <td align="center">{{ $user->age or '' }}</td>
            <td align="center">
                {{ $user->country or '' }}{{ $user->province ? '.' . $user->province : '' }}{{ $user->city ? '.' . $user->city : '' }}
            </td>
            <td align="center">
                <label class="show">
                    @if ($user->display == 1)
                        是
                    @else
                        否
                    @endif
                </label>
                &nbsp;&nbsp;<label class="changeShow" onclick="toggleShow(this, {{ $user->id }})">切换</label>
            </td>
            <td align="center">{{ $user->created_at }}</td>
            <td align="center">
                <form action="{{url('admin/user/'.$user->id)}}" method="POST">
                    <a href="{{url('admin/user/'.$user->id.'/edit')}}">编辑</a> |
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
     * 控制菜品的显示与否
     * @param  {[type]} ele [description]
     * @param  {[type]} id  [该菜品的ID]
     * @return {[type]}     [description]
     */
    function toggleShow(ele, id){
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {uid:id, _token:"{{csrf_token()}}"},
            url:"{{url('admin/user/toggleDisplay')}}",
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

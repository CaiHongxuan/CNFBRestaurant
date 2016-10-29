@extends('layouts.app')

@section('sub-css')
    <link rel="stylesheet" type="text/css" href="/css/style.css">
@endsection

@section('content')
<!-- 当前位置 -->
<div id="urHere"><a href="/admin">管理首页</a><b>></b><strong>菜品列表</strong> </div>
<div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="/admin/food/create" class="actionBtn add">添加菜品</a>菜品列表</h3>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
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
            <th width="100" align="left">名称</th>
            <th width="60" align="center">价格</th>
            <th width="220" align="center">描述</th>
            <th align="center">图片</th>
            <th width="60" align="center">剩余份数</th>
            <th width="80" align="center">所属分类</th>
            <th width="60" align="center">排序</th>
            <th width="100" align="center">是否显示</th>
            <th width="60" align="center">已卖出</th>
            <th width="60" align="center">更新时间</th>
            <th width="80" align="center">操作</th>
        </tr>
        @foreach ($foods as $food)
        <tr>
            <td align="center">{{ $food['id'] }}</td>
            <td align="left">{{ $food['name'] }}</td>
            <td align="right">{{ $food['price'] }}&yen;</td>
            <td align="center">{{ $food['description'] }}</td>
            <td align="left">
                @if (isset($food['media_arr']))
                    <img src="{{ $food['media_arr']['0'] }}?imageView2/0/w/40/h/40">
                @endif
            </td>
            <td align="center">@if ($food['rest'] >= 0) {{ $food['rest'] }} @else 无限量 @endif</td>
            <td align="center">{{ $food['cate'] }}</td>
            <td align="center">{{ $food['sort'] }}</td>
            <td align="center">
                <label class="show">
                    @if ($food['display'] == 1)
                        是
                    @else
                        否
                    @endif
                </label>
                &nbsp;&nbsp;<label class="changeShow" onclick="toggleShow(this, {{$food['id']}})">切换</label>
            </td>
            <td align="center">{{ $food['sold'] }}</td>
            <td align="center">{{ $food['updated_at'] }}</td>
            <td align="center">
                <form action="{{url('admin/food/'.$food['id'])}}" method="POST">
                    <a href="{{url('admin/food/'.$food['id'].'/edit')}}">编辑</a> |
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
        htmlobj = $.ajax({
            type: "POST",
            dataType: "json",
            data: {fid:id, _token:"{{csrf_token()}}"},
            url:"{{url('admin/food/toggleDisplay')}}",
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

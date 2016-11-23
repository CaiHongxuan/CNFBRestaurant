@extends('layouts.app')

@section('sub-css')
    <link rel="stylesheet" type="text/css" href="/css/style.css">
@endsection

@section('content')
<!-- 当前位置 -->
<div id="urHere"><a href="/admin">管理首页</a><b>></b><strong>订单列表</strong> </div>
<div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="/admin/order/create" class="actionBtn add">添加订单</a>订单列表</h3>

    @if (count($errors) > 0)
        <div class="alert alert-{{ session('success') ? session('success') : 'danger' }}">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>提示!</h4>
            {!! implode('<br>', $errors->all()) !!}
        </div>
    @endif

    <!-- <div class="filter">
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
    </div> -->

    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic tableOrder" style="border-collapse:separate;">
        <tr>
            <th width="220" align="center">商品名称</th>
            <th width="60" align="center">单价</th>
            <th width="60" align="center">数量</th>
            <th width="60" align="center">小计</th>
            <th width="60" align="center">备注</th>
            <th width="80" align="center">操作</th>
        </tr>
        @foreach ($orders as $order)
            <tr>
                <td colspan="9" class="blank"></td>
            </tr>
            <tr style="border-collapse:separate;border-spacing:0px 10px;">
                <td class="contentHead" colspan="9">&nbsp;&nbsp;
                    <span><b>订单号：</b>{{ $order['bookid'] }}</span>
                    <span><b>餐桌名：</b>{{ $order['table_id'] }}</span>
                    <span><b>下单者：</b>{{ $order['user'][0]['nickname'] }}</span>
                    <span><b>下单时间：</b>{{ $order['created_at'] }}</span>
                    <span><b>总额：</b>{{ $order['total'] }}&yen;</span>
                    <span><b>交易状态：</b>
                        @if ($order['status'] == 0)
                            未付款
                        @elseif ($order['status'] == 1)
                            已付款
                        @elseif ($order['status'] == 2)
                            撤销订单
                        @endif
                    </span>
                    <span><b>备注：</b>{{ $order['remarks'] }}</span>
                </td>
            </tr>
            @foreach ($order['foods'] as $food)
                <tr>
                    <td align="center">{{ $food['name'] }}</td>
                    <td align="right">{{ $food['price'] }}&yen;</td>
                    <td align="center">{{ $food['count'] }}</td>
                    <td align="right">{{ $food['price'] * $food['count'] }}&yen;</td>
                    <td align="center">{{ $food['remarks'] }}</td>
                    @if ($loop->first)
                        <td rowspan="{{ $loop->count }}" align="center">
                            <form action="{{url('admin/order/'.$order['id'])}}" method="POST">
                                <!-- <a href="{{url('admin/order/'.$order['id'])}}">详情</a> | -->
                                <a href="{{url('admin/order/'.$order['id'].'/edit')}}">编辑</a> |
                                {{method_field('DELETE')}}
                                {{csrf_field()}}
                                <button type="submit" class="del" onclick="javascript:if(confirm('确定删除？')){return true;}else{return false;}">删除</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
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

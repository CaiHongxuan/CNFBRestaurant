@extends('layouts.app')

@section('sub-css')
    <link rel="stylesheet" type="text/css" href="/css/style.css">
@endsection

@section('content')
<!-- 当前位置 -->
<div id="urHere"><a href="/admin">管理首页</a><b>></b><strong>菜品列表</strong> </div>
<div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="/admin/food" class="actionBtn">菜品列表</a>编辑菜品</h3>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>
                提示!
            </h4> <strong>新增失败：输入不符合要求</strong> {!! implode('<br>', $errors->all()) !!}
        </div>
    @endif

    <form action="{{url('admin/food/' . $food->id)}}" method="post" enctype="multipart/form-data">
        {{method_field('PATCH')}}
        {!! csrf_field() !!}
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
            <tr>
                <td width="80" align="right">菜品名称</td>
                <td>
                    <input type="text" name="food_name" value="{{ $food->name }}" size="40" class="inpMain" />
                </td>
            </tr>
            <tr>
                <td width="80" align="right">价格</td>
                <td>
                    <input type="text" name="food_price" value="{{ $food->price }}" size="10" class="inpMain" />
                </td>
            </tr>
            <tr>
                <td width="80" align="right">描述</td>
                <td>
                    <textarea name="description" cols="83" rows="4" class="textArea">{{ $food->description }}</textarea>
                </td>
            </tr>
            <tr>
                <td width="80" align="right">所属分类</td>
                <td>
                    <select name="cate">
                        @foreach ($cates as $cate)
                            @if ($food->cate_id == $cate->id)
                                <option value="{{ $cate->id }}" selected>{{ $cate->name }}</option>
                            @else
                                <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">剩余份数</td>
                <td>
                    <input type="text" name="rest" value="{{ $food->rest }}" size="5" class="inpMain" />
                    <span class="cue">-1代表无限量</span>
                </td>
            </tr>
            <tr>
                <td align="right">图片</td>
                <td>
                    <input type="file" name="media" value="{{ $food->media_id }}" size="5" class="inpMain" />
                    <span class="cue">最大不超过2M</span>
                </td>
            </tr>
            <tr>
                <td align="right">排序</td>
                <td>
                    <input type="text" name="sort" value="{{ $food->sort }}" size="5" class="inpMain" />
                </td>
            </tr>
            <tr>
                <td align="right">是否显示</td>
                <td>
                    <input name="display" type="radio" value="1" @if($food->display==1) checked="checked" @endif>是&nbsp;
                    <input name="display" type="radio" value="0" @if($food->display==0) checked="checked" @endif>否
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

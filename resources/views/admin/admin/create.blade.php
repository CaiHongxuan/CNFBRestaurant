@extends('layouts.app')

@section('sub-css')
    <link rel="stylesheet" type="text/css" href="/css/style.css">
@endsection

@section('content')
<!-- 当前位置 -->
<div id="urHere"><a href="/admin">管理首页</a><b>></b><strong>餐台管理</strong> </div>
<div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
    <h3><a href="/admin/admin" class="actionBtn">网站管理员</a>新增管理员</h3>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>
                提示!
            </h4> <strong>新增失败：输入不符合要求</strong> <br>{!! implode('<br>', $errors->all()) !!}
        </div>
    @endif

    <form action="{{ url('/register') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
            <tr>
                <td width="80" align="right">名称</td>
                <td>
                    <input type="text" name="name" value="{{ old('name') }}" size="40" class="inpMain" required />
                    @if ($errors->has('name'))
                        <span class="cue">{{ $errors->first('name') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td width="80" align="right">邮箱</td>
                <td>
                    <input type="email" name="email" value="" size="40" class="inpMain" required />
                    @if ($errors->has('email'))
                        <span class="cue">{{ $errors->first('email') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td width="80" align="right">密码</td>
                <td>
                    <input type="password" name="password" value="" size="40" class="inpMain" required />
                    @if ($errors->has('password'))
                        <span class="cue">{{ $errors->first('password') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td width="80" align="right">确认密码</td>
                <td>
                    <input type="password" name="password_confirmation" value="" size="40" class="inpMain" required />
                    @if ($errors->has('password_confirmation'))
                        <span class="cue">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td align="right">头像</td>
                <td>
                    <input type="file" name="media" value="0" size="5" class="inpMain" />
                    <span class="cue">小于2M且格式为 jpg，jpeg，gif，png 中的一种</span>
                </td>
            </tr>
            <tr>
                <td align="right">是否启用</td>
                <td>
                    <input name="status" type="radio" value="1" checked="checked">是&nbsp;
                    <input name="status" type="radio" value="0">否
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input name="submit" class="btn" type="submit" value="创建" />
                </td>
            </tr>
        </table>
    </form>
</div>
@endsection

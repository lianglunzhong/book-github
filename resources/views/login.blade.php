@extends('master')

@section('title', '登录')

@section('crumb')

<div class="ui segment" style="border:0;box-shadow: 0 0 0 0;">
  <div class="ui container">
    <div class="ui small breadcrumb" style="background-color: #fff;">
      <a class="section" href="/">首页</a>
      <div class="divider"> / </div>
      <a class="active section" href="javascript:void();" style="cursor: default;">登录</a>
    </div>
    <div class="ui divider" style="margin-top: 1px;"></div>
  </div>
</div>

@endsection

@section('content')

<div class="ui basic segment">
    <div class="ui container">
        <h4 class="ui dividing header">账号信息</h4>
        <div class="four fields">
            <div class="required field">
                <label>账号</label>
                <div class="ui icon input">
                    <input type="text" placeholder="邮箱或手机号" name="username">
                    <i class="user icon"></i>
                </div>
            </div>
            <div class="required field">
                <label>密码</label>
                <div class="ui icon input">
                    <input type="password" placeholder="不少于6位" name="password">
                    <i class="lock icon"></i>
                </div>
            </div>
            <div class="required field">
                <label>验证码</label>
                <div class="ui icon input">
                    <input type="text" placeholder="请输入验证码" name="validate_code">
                </div>
                <div>
                    <a href="javascript:void();"><img src="/service/validate_code/create" class="bk_validate_code"/></a>
                </div>
            </div>
            <div class="ui teal button" onclick="_onLoginClick();">
                登录
            </div>
        </div>
    </div>
</div>

@endsection

@section('my-js')
<script type="text/javascript">
    //获取验证码
    $(".bk_validate_code").click(function () {
        $(this).attr('src', '/service/validate_code/create?random='+Math.random());
    });

    //登录
    function _onLoginClick()
    {
        username = $('input[name=username]').val();
        password = $('input[name=password]').val();
        validate_code = $('input[name=validate_code]').val();

        //账号不能为空
        if(username == '') {
            $('.bk_toptips').show();
            $(".bk_toptips span").html('账号不能为空');
            setTimeout(function() {$('.bk_toptips').hide();}, 2000);
            return ;
        }

        //密码不能为空
        if(password == '') {
            $('.bk_toptips').show();
            $(".bk_toptips span").html('密码不能为空');
            setTimeout(function() {$('.bk_toptips').hide();}, 2000);
            return ;
        }

        //密码长度不能小于6
        if(password.length < 6) {
            $('.bk_toptips').show();
            $(".bk_toptips span").html('密码长度不小于6');
            setTimeout(function() {$('.bk_toptips').hide();}, 2000);
            return ;
        }

        //验证码不能为空
        if(validate_code == '') {
            $('.bk_toptips').show();
            $(".bk_toptips span").html('验证码不能为空');
            setTimeout(function() {$('.bk_toptips').hide();}, 2000);
            return ;
        }

        $.ajax({
            type: 'POST',
            url: '/service/login',
            dataType: 'json',
            cache: false,
            data: {username: username, password: password, validate_code: validate_code, _token:'{{ csrf_token() }}'},
            success: function(data) {
                if(data == null) {
                  $('.bk_toptips').show();
                  $('.bk_toptips span').html('服务端错误');
                  setTimeout(function() {$('.bk_toptips').hide();}, 2000);
                  return;
                }
                if(data.status != 0) {
                  $('.bk_toptips').show();
                  $('.bk_toptips span').html(data.message);
                  setTimeout(function() {$('.bk_toptips').hide();}, 2000);
                  return;
                }

                $('.bk_toptips').show();
                $('.bk_toptips span').html('登录成功');
                setTimeout(function() {$('.bk_toptips').hide();}, 2000);
                
                // window.location.href = '/';
                window.location.href = "{{ $return_url }}";
            },
            error: function(xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }

</script>
@endsection

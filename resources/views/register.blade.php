@extends('master')

@section('title', '注册')

@section('crumb')

<div class="ui segment" style="border:0;box-shadow: 0 0 0 0;">
  <div class="ui container">
    <div class="ui small breadcrumb" style="background-color: #fff;">
      <a class="section" href="/">首页</a>
      <div class="divider"> / </div>
      <a class="active section" href="javascript:void();" style="cursor: default;">注册</a>
    </div>
    <div class="ui divider" style="margin-top: 1px;"></div>
  </div>
</div>

@endsection

@section('content')

<div class="ui segment" style="border:0;box-shadow: 0 0 0 0;">
  <div class="ui container">
    <div class="ui grid">
      <div class="two wide column"></div>
      <div class="six wide column">
        <div class="ui fluid two item secondary pointing menu">
          <a class="active item" id="phone-a">
            <i class="home icon"></i>手机注册
          </a>
          <a class="item" id="email-a">
            <i class="mail icon"></i>邮箱注册
          </a>
          <!--用户判断是手机还是邮箱注册-->
          <input type="hidden" name="register_type" value="phone">
        </div>
        <div style="padding-top: 40px;padding-right: 50px;">
          <form class="ui form" id="phone">
            <div class="field">
              <label>手机号码</label>
              <input placeholder="请输入手机号" type="text" name="phone">
              <span class="phone-error"></span>
            </div>
            <div class="field">
              <label>登录密码</label>
              <input placeholder="请输入登录密码" type="password" name="passwd_phone">
              <span class="password-error"></span>
            </div>
            <div class="field">
              <label>确认密码</label>
              <input placeholder="请再次输入密码" type="password" name="passwd_phone_cfm">
              <span class="confirm-password-error"></span>
            </div>
            <div class="two fields">
              <div class="field">
                <label>验证码</label>
                  <input placeholder="请输入手机验证码" type="text" style="width:150px;" name="phone_code">
                  <span class="code-error"></span>
              </div>
              <div class="field" style="display: inline;padding-top: 32px;text-align: right;">
                <p class="bk_important bk_phone_code_send">发送验证码</p>
              </div>
            </div>
            <div class="field">
              <div class="fluid ui teal button" onclick="onRegisterClick();">注册</div>
            </div>
          </form>
          <form class="ui form" id="email" style="display: none;">
            <div class="field">
              <label>邮箱地址</label>
              <input placeholder="请输入邮箱" type="text" name="email">
              <span class="email-error"></span>
            </div>
            <div class="field">
              <label>登录密码</label>
              <input placeholder="请输入登录密码" type="password" name="passwd_email">
              <span class="email-password-error"></span>
            </div>
            <div class="field">
              <label>确认密码</label>
              <input placeholder="请再次输入密码" type="password" name="passwd_email_cfm">
              <span class="email-confirm-error"></span>
            </div>
            <div class="two fields">
              <div class="field">
                <label>验证码</label>
                  <input placeholder="请输入验证码" type="text" style="width:120px;" name="validate_code">
                  <span class="email-code-error"></span>
              </div>
              <div class="field" style="padding-top: 13px;">
                <a href="javascript:void();"><img src="/service/validate_code/create" class="bk_validate_code"/></a>
              </div>
            </div>
            <div class="field">
              <div class="fluid ui teal button" onclick="onRegisterClick();">注册</div>
            </div>
          </form>
        </div>
      </div>
      <div class="one wide column"></div>
      <div class="five wide column" style="border-left: 1px solid #E7E7E7;padding-top: 100px;">
        <div style=" width:100%; text-align:left; margin-left:15px; margin-right:15px;">
             <hr style="width:20%;vertical-align:middle; display:inline-block;border:1px solid #E7E7E7;"/>&nbsp;&nbsp;&nbsp;&nbsp;使用合作方账号登录&nbsp;&nbsp;&nbsp;&nbsp;<hr style="width:20%;vertical-align:middle; display:inline-block;border:1px solid #E7E7E7;"/>
        </div>
      </div>
      <div class="two wide column"></div>
    </div>
  </div>
</div>

<!-- <div class="ui segment">
  
</div> -->



@endsection

@section('my-js')
<script type="text/javascript">
    //获取图片验证码
    $(".bk_validate_code").click(function () {
        $(this).attr('src', '/service/validate_code/create?random='+Math.random());
    });

    //手机注册和邮箱注册切换
    $(".menu a").click(function () {
        $(this).addClass('active').siblings().removeClass('active');
        if($(this).attr('id') == 'phone-a') {
          $('#phone').show();
          $('#email').hide();
          $('input[name=register_type]').val('phone');
        } else if($(this).attr('id') == 'email-a') {
          $('#email').show();
          $('#phone').hide();
          $('input[name=register_type]').val('email');
        }
    });
</script> 


<script type="text/javascript">
    /*
     *手机注册，获取手机短信验证码
     */
    var enable = true;
    $('.bk_phone_code_send').click(function(event) {
        if(enable == false) {
            return;
        }

        var phone = $('input[name=phone]').val();
        //手机号不为空
        if(phone == '') {
          $('.phone-error').html('手机号不能为空');
          $('.phone-error').show();
          // $('input[name=phone]').addClass('input-error');
          setTimeout(function() {$('.phone-error').hide();}, 3000);
          return;
        }

        //手机号格式
        if(phone.length != 11 || phone[0] != '1') {
          console.log(phone.length);
          console.log(phone[0]);

          $('.phone-error').html('手机号格式不正确');
          $('.phone-error').show();
          // $('input[name=phone]').addClass('input-error');
          setTimeout(function() {$('.phone-error').hide();}, 3000);
          return;
        }

        $(this).removeClass('bk_important');
        $(this).addClass('bk_summary');
        enable = false;
        var num = 60;
        var interval = window.setInterval(function() {
            $('.bk_phone_code_send').html(--num + 's 后重新发送');
            if(num == 0) {
                $('.bk_phone_code_send').addClass('bk_important');
                $('.bk_phone_code_send').removeClass('bk_summary');
                enable = true;
                window.clearInterval(interval);
                $('.bk_phone_code_send').html('重新发送');
            }
        },1000);

        $.ajax({
            type: 'POST',
            url: '/service/validate_phone/send',
            dataType: 'json',
            cache: false,
            data: {phone:phone, _token:'{{ csrf_token() }}'},
            success: function(data) {
                if(data == null) {
                  $('.bk_toptips').show();
                  $(".bk_toptips span").html('服务端错误');
                  setTimeout(function() {$('.bk_toptips').hide();}, 2000);
                  return;
                }
                if(data.status != 0) {
                  $('.bk_toptips').show();
                  $(".bk_toptips span").html(data.message);
                  setTimeout(function() {$('.bk_toptips').hide();}, 2000);
                  return;
                }
                $('.bk_toptips').show();
                $(".bk_toptips span").html('发送成功');
                setTimeout(function() {$('.bk_toptips').hide();}, 2000);
            },
            error: function(xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    });
</script>

<script type="text/javascript">
  /*
   *注册按钮
   */
  function onRegisterClick() {
    var register_type = $('input[name=register_type]').val();

    var email = '';
    var phone = '';
    var password = '';
    var confirm = '';
    var phone_code = '';
    var validate_code = '';

    if(register_type == 'phone') {
      phone = $('input[name=phone').val();
      password = $('input[name=passwd_phone]').val();
      confirm = $('input[name=passwd_phone_cfm]').val();
      phone_code = $('input[name=phone_code]').val();

      if(verifyPhone(phone, password, confirm, phone_code) == false) {
        return;
      }
    } else if(register_type == 'email') {
      email = $('input[name=email]').val();
      password = $('input[name=passwd_email]').val();
      confirm = $('input[name=passwd_email_cfm]').val();
      validate_code = $('input[name=validate_code]').val();
      if(verifyEmail(email, password, confirm, validate_code) == false) {
        return;
      }
    }

    $.ajax({
      type: 'POST',
      url: '/service/register',
      dataType: 'json',
      cache: false,
      data: {phone: phone, email: email, password: password, confirm: confirm,phone_code: phone_code, validate_code: validate_code, _token:'{{ csrf_token() }}'},
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
        $('.bk_toptips span').html('注册成功');
        setTimeout(function() {$('.bk_toptips').hide();}, 2000);
      },
      error: function(xhr, status, error) {
        console.log(xhr);
        console.log(status);
        console.log(error);
      }
    });
  }


  /*
   *手机注册，输入手机号码、密码、验证码验证
  function checkphone(value) {
    var phone = value;
    //手机号不为空
    if(phone == '') {
      $('.phone-error').html('手机号不能为空');
      $('.phone-error').show();
      // $('input[name=phone]').addClass('input-error');
      return;
    }

    //手机号格式
    if(phone.length != 11 || phone[0] != '1') {
      $('.phone-error').html('手机号格式不正确');
      $('.phone-error').show();
      // $('input[name=phone]').addClass('input-error');
      return;
    }
    $('.phone-error').hide();
  }
  */
  function verifyPhone(phone, password, confirm, phone_code) {
    //手机号不为空
    if(phone == '') {
      $('.phone-error').html('请输入手机号');
      $('.phone-error').show();
      // $('input[name=phone]').addClass('input-error');
      setTimeout(function() {$('.phone-error').hide();}, 2000);
      return false;
    }

    //手机号格式
    if(phone.length != 11 || phone[0] != '1') {
      $('.phone-error').html('手机号格式不正确');
      $('.phone-error').show();
      // $('input[name=phone]').addClass('input-error');
      setTimeout(function() {$('.phone-error').hide();}, 2000);
      return false;
    }
    //密码不能为空
    if(password == '') {
      $('.password-error').html('密码不能为空');
      $('.password-error').show();
      // $('input[name=phone]').addClass('input-error');
      setTimeout(function() {$('.password-error').hide();}, 2000);
      return false;
    }
    //密码长度不能小于6
    if(password.length < 6) {
      $('.password-error').html('密码不能少于6位');
      $('.password-error').show();
      // $('input[name=phone]').addClass('input-error');
      setTimeout(function() {$('.password-error').hide();}, 2000);
      return false;
    }
    //两次密码必须相同
    if(password != confirm) {
      $('.confirm-password-error').html('两次密码不相同');
      $('.confirm-password-error').show();
      // $('input[name=phone]').addClass('input-error');
      setTimeout(function() {$('.confirm-password-error').hide();}, 2000);
      return false;
    }
    //手机验证码不能为空
    if(phone_code == '') {
      $('.code-error').html('验证码不能为空');
      $('.code-error').show();
      setTimeout(function() {$('.code-error').hide();}, 2000);
      return false;
    }
    //手机验证码为6位
    if(phone_code.length != 6) {
      $('.code-error').html('手机验证码为7位');
      $('.code-error').show();
      setTimeout(function() {$('.code-error').hide();}, 2000);
      return false;
    }
    return true;
  }


  /*
   *邮箱注册，输入邮箱、密码、验证码验证
   */
  function verifyEmail(email, password, confirm, validate_code) {
    //邮箱不为空
    if(email == '') {
      $('.email-error').html('请输入邮箱');
      $('.email-error').show();
      setTimeout(function() {$('.email-error').hide();}, 2000);
      return false;
    }

    //邮箱格式
    if(email.indexOf('@') == -1 || email.indexOf('.') == -1) {
      $('.email-error').html('邮箱格式不正确');
      $('.email-error').show();
      setTimeout(function() {$('.email-error').hide();}, 2000);
      return false;
    }
    //密码不能为空
    if(password == '') {
      $('.email-password-error').html('密码不能为空');
      $('.email-password-error').show();
      setTimeout(function() {$('.email-password-error').hide();}, 2000);
      return false;
    }
    //密码长度不能小于6
    if(password.length < 6) {
      $('.email-password-error').html('密码不能少于6位');
      $('.email-password-error').show();
      setTimeout(function() {$('.email-password-error').hide();}, 2000);
      return false;
    }
    //两次密码必须相同
    if(password != confirm) {
      $('.email-confirm-error').html('两次密码不相同');
      $('.email-confirm-error').show();
      setTimeout(function() {$('.email-confirm-error').hide();}, 2000);
      return false;
    }
    //验证码不能为空
    if(validate_code == '') {
      $('.email-code-error').html('验证码不能为空');
      $('.email-code-error').show();
      setTimeout(function() {$('.email-code-error').hide();}, 2000);
      return false;
    }
    //验证码为4位
    if(validate_code.length != 4) {
      $('.email-code-error').html('验证码为4位');
      $('.email-code-error').show();
      setTimeout(function() {$('.email-code-error').hide();}, 2000);
      return false;
    }
    return true;
  }

</script>
@endsection

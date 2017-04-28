@extends('master')

@section('title', '生成订单')

@section('crumb')

<div class="ui segment" style="border:0;box-shadow: 0 0 0 0;">
  <div class="ui container">
    <div class="ui three  ordered steps" style="border:0;height: 45px;border-radius: 0;">
      <div class="active step">
        <div class="content">
          <div class="title">订单确认</div>
          <div class="description">请确认产品及发货地址</div>
        </div>
      </div>
      <div class="disabled step">
        <div class="content">
          <div class="title">订单支付</div>
          <div class="description">输入帐单信息</div>
        </div>
      </div>
      <div class="disabled step"">
        <div class="content">
          <div class="title">支付完成</div>
          <div class="description">确认订单详细信息</div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('content')

<div class="ui basic segment">
    <div class="ui container">
        <div class="ui grid">
            <div class="row">
                <div class="ten wide column">
                    <div class="ui basic segment">
                            <h4 class="ui header" style="margin-bottom: 4px;">收货信息</h4>
                            <div class="ui divider" style="margin-bottom: 4px;margin-top: 4px;"></div>
                            <div class="ui segment">
                                <div class="ui divided list">
                                    <div class="item">
                                        <i class="user icon"></i>
                                        <div class="content">
                                            <div class="description"><b>姓名：</b>梁伦忠</div>
                                        </div>
                                    </div>
                                    <div class="item" style="padding-top: 12px;padding-bottom: 10px;">
                                        <i class="call square icon"></i>
                                        <div class="content">
                                            <div class="description"><b>电话：</b>18061445461</div>
                                        </div>
                                    </div>
                                    <div class="item" style="padding-top: 12px;">
                                        <i class="home icon"></i>
                                        <div class="content">
                                            <div class="description"><b>地址：</b>江苏省南京市玄武区仙鹤门</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="ui header" style="margin-top: 60px;margin-bottom: 4px;">支付方式</h4>
                            <div class="ui divider" style="margin-bottom: 4px;margin-top: 4px;"></div>
                            <div class="ui basic segment">
                                <div class="content">
                                    <div class="ui input">
                                        <input type="radio" name="payment" value="1" id="alipay" style="width: 20px;height: 20px;cursor: pointer" checked="checked">
                                    </div>
                                    <label for="alipay" style="cursor: pointer">
                                        <img src="/images/alipay.jpg" style="width:200px;height: 65px;">
                                    </label>
                                </div>
                            </div>
                            <div class="ui basic segment">
                                <div class="content">
                                    <div class="ui input">
                                        <input type="radio" name="payment" value="2" id="watchpay" style="width: 20px;height: 20px;cursor: pointer">
                                    </div>
                                    <label for="watchpay" style="cursor: pointer">
                                        <img src="/images/watchpay.jpg" style="width:200px;height: 65px;">
                                    </label>
                                </div>
                            </div>
                            <div class="ui basic segment" style="width: 50%;">
                                <div class="fluid ui teal button" onclick="_toCommit();">提交订单</div>
                            </div>
                    </div>
                </div>
                <div class="six wide column">
                    <div class="ui segment">
                        <div class="ui grid">

                            @foreach ($cart_items as $cart_item)
                                <div class="row">
                                    <div class="six wide column">
                                        <div class="ui image" style="padding-left: 10px;">
                                            <img src="/images/book.jpg">
                                        </div>
                                    </div>
                                    <div class="ten wide column" style="vertical-align: middle; ">
                                        <h4 class="ui header">{{ $cart_item->product->name }}</h4>
                                        <span>价格：<b style="color: red;">${{ $cart_item->product->price }}</b></span>
                                        <br>
                                        <span>数量：{{ $cart_item->count }}</span>
                                    </div>
                                </div>
                            @endforeach

                            <div class="ui divider"></div>
                            <div class="row" style="line-height: 32px;">
                                <div class="one wide column"></div>
                                <div class="seven wide column">
                                    <span>商品金额：</span>
                                    <br>
                                    <span>运费金额：</span>
                                    <br>
                                    <span>合计金额：</span>
                                </div>
                                <div class="six wide column">
                                    <b>${{ $p_price }}</b>
                                    <br>
                                    <b>${{ $s_price }}.00</b>
                                    <br>
                                    <b style="color: red;">${{ $total_price }}</b>
                                </div>
                                <div class="two wide column"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('my-js')

<script type="text/javascript">
    function _toCommit() {
        var payway = $('input:radio[name=payment]:checked').val();
        if(payway != 1 && payway != 2) {
            $('.bk_toptips').show();
            $(".bk_toptips span").html('请选择支付方式');
            setTimeout(function() {$('.bk_toptips').hide();}, 2000);
            return;
        }
        
        var product_ids = '{{ $product_ids }}';
        if(product_ids == '') {
            $('.bk_toptips').show();
            $(".bk_toptips span").html('没有产品不能提交该订单');
            setTimeout(function() {$('.bk_toptips').hide();}, 2000);
            return;
        }

        var total_price = '{{ $total_price }}';

        $.ajax({
            type: 'POST',
            url: '/service/order_commit',
            dataType: 'json',
            cache: false,
            data: {product_ids:product_ids,payway:payway,total_price:total_price,_token:'{{ csrf_token() }}'},
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

                location.href = '/order_list';
                $('.bk_toptips').show();
                $(".bk_toptips span").html('success');
                setTimeout(function() {$('.bk_toptips').hide();}, 2000);

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

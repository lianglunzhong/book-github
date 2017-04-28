@extends('master')

@section('title','购物车')

@section('crumb')

<div class="ui segment" style="border:0;box-shadow: 0 0 0 0;">
  <div class="ui container">
    <div class="ui small breadcrumb" style="background-color: #fff;">
      <a class="section" href="/">首页</a>
      <div class="divider"> / </div>
      <a class="active section" href="javascript:void();" style="cursor: default;">购物车</a>
      <div class="ui divider" style="margin-top: 1px;"></div>
    </div>
  </div>
</div>

@endsection

@section('content')
<div class="ui segment" style="border:0;box-shadow: 0 0 0 0;">
    <div class="ui container">
        <table class="ui very basic table">
            <thead>
                <tr>
                    <th style="width: 8%;text-align: left;">
                        <div class="ui checkbox" style="padding-top: 8px;">
                            <input style="left: 20px;" type="checkbox" name="" class="allCheck" id="selectall1">
                            <label style="color: #969696;font-size: 14px;cursor: pointer;" for="selectall1">全选</label>
                        </div>
                    </th>
                    <th style="width: 46%;">商品信息</th>
                    <th style="width: 10%;">单价(美元)</th>
                    <th style="width: 20%;">数量</th>
                    <th style="width: 10%;">金额(美元)</th>
                    <th style="width: 6%;">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart_items as $cart_item)
                <tr>
                    <td style="padding-left: 2%;">
                        <input type="checkbox" name="cart_item" id="{{ $cart_item->product->id }}" style="width: 15px;height: 15px;box-sizing: border-box;background-color: #fff;">
                    </td>
                    <td>
                        <div class="ui grid">
                            <div class="row">
                                <div class="four wide column">
                                    <a href="/product/{{ $cart_item->product->id }}">
                                        <img style="width: 90px;" src="{{ $cart_item->product->preview }}">
                                    </a>
                                </div>
                                <div class="twelve wide column" style="padding-top: 22px;">
                                    <h3>{{ $cart_item->product->name }}</h3>
                                    <p>{{ $cart_item->product->summary }}</p>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align: center;">${{ $cart_item->product->price }}</td>
                    <td  style="text-align: center;">
                        <div class="ui basic segment" style="padding-top: 5px;">
                            <a style="cursor: pointer;" class="minus-qty"><i class="large minus square outline icon" style="margin-right: -5px;"></i></a>
                            <div class="ui input">
                                <input type="text" value="{{ $cart_item->count }}" style="width: 50px;height: 28px;" id="qty" onchange="changeQty(this);">
                            </div>
                            <a style="cursor: pointer;" class="plus-qty"><i class="large plus square outline icon" style="margin-left: -5px;"></i></a>
                        </div>
                    </td>
                    <td style="text-align: center;" id="total_price">${{ $cart_item->product->price * $cart_item->count }}</td>
                    <td style="text-align: center;"><a href="javascript:void(0);" onclick="_onDeleteOne({{ $cart_item->product->id }});" style="cursor: pointer;">删除</a></td>
                </tr>
                @endforeach
              </tbody>
        </table>
        <div class="ui divider"></div>
        <div class="ui basic segment" style="padding-top: 0">
            <div class="ui grid">
                <div class="row">
                    <div class="three wide column">
                        <div class="ui checkbox">
                            <input style="left: 20px;" type="checkbox" name="" class="allCheck" id="selectall2">
                            <label style="color: #969696;font-size: 12px;cursor: pointer;" for="selectall2">全选</label>
                        </div>
                    </div>
                    <div class="three wide column">
                        <a style="color: #333;font-size: 12px;cursor: pointer;" onclick="_onDeleteAll();">批量删除</a>
                    </div>
                    <div class="four wide column" style="color: #969696;font-size: 12px;">已选择0件商品</div>
                    <div class="right floated  right aligned three wide column">
                        <span style="color: #969696;font-size: 12px;">总计:</span>
                        <label style="color: red;font-size: 14px;">$100.89</label>
                    </div>
                    <div class="right floated  right aligned three wide column">
                        <form action="/order_checkout" method="post">
                            <!-- <input type="hidden" name="_token" value="<?php //echo csrf_token();?>"> -->
                            <!-- <?php //echo csrf_field();?> -->
                            {!! csrf_field() !!}
                            <input type="hidden" name="product_ids">
                            <button id="order_checkout" type="submit" style="display: none;"></button>
                        </form>
                        <div class="ui teal  button" onclick="_toCharge();">结算</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('my-js')

<script type="text/javascript">
    /*
     *全选框点击事件
     */
    $('.allCheck').click(function() {
        if(this.checked) {
            $('[name=cart_item]:checkbox').prop('checked', true);
        } else {
            $('[name=cart_item]:checkbox').prop('checked', false);
        }
        $(".allCheck").prop("checked", this.checked);
    });

    /*
     *批量删除
     */
    function _onDeleteAll()
    {
        var product_ids_arr = [];

        $('input:checkbox[name=cart_item]').each(function() {
            if($(this).prop('checked')) {
                product_ids_arr.push($(this).attr('id'));
            }
        });

        if(product_ids_arr.length == 0) {
            // alert('请选择需要删除的产品。');
            $('.bk_toptips').show();
            $(".bk_toptips span").html('请选择需要删除的产品');
            setTimeout(function() {$('.bk_toptips').hide();}, 2000);
            return ;
        }

        $.ajax({
            type: 'GET',
            url: '/service/cart/deleteall',
            dataType: 'json',
            cache: false,
            data: {product_ids:product_ids_arr+''},
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

                location.reload();
            },
            error: function(xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }

    /*
     *单个删除
     */
    function _onDeleteOne(product_id)
    {

        $.ajax({
            type: 'GET',
            url: '/service/cart/deleteone/'+product_id,
            dataType: 'json',
            cache: false,
            data: {},
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

                location.reload();
            },
            error: function(xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }

    /*
     *结算
     */
    function _toCharge()
    {
        var product_ids_arr = [];

        $('input:checkbox[name=cart_item]').each(function () {
            if($(this).prop('checked')) {
                product_ids_arr.push($(this).attr('id'));
            }
        });



        if(product_ids_arr.length == 0) {
            $('.bk_toptips').show();
            $(".bk_toptips span").html('请选择产品');
            setTimeout(function() {$('.bk_toptips').hide();}, 2000);
            return ;
        }

        // location.href = '/order_commit/' + product_ids_arr;
        $('input[name=product_ids').val(product_ids_arr+'');
        $('#order_checkout').click();

    }
</script>

@endsection
@extends('master')

@section('title',$product->name)

@section('crumb')

<div class="ui segment" style="border:0;box-shadow: 0 0 0 0;">
  <div class="ui container">
    <div class="ui small breadcrumb" style="background-color: #fff;">
      <a class="section" href="/">首页</a>
      <div class="divider"> / </div>
      <a class="section" href="/product/category_id/{{ $product->category_id }}">分类</a>
      <div class="divider"> / </div>
      <a class="active section" href="javascript:void();" style="cursor: default;">产品</a>
    </div>
    <div class="ui divider" style="margin-top: 1px;"></div>
  </div>
</div>

@endsection

@section('content')
<!-- <link href="http://www.jq22.com/jquery/bootstrap-3.3.4.css" rel="stylesheet"> -->
<!-- <link rel="stylesheet" type="text/css" href="/css/star-rating.min.css"></li> -->

<div class="ui segment" style="border:0;box-shadow: 0 0 0 0;">
    <div class="ui container">
      <div class="ui page grid">
        <div class="five wide column">
          <div>
            <img src="/images/book.jpg" style="width: 90%;">
          </div>
        </div>
        <div class="eleven wide column">
            <h1 class="ui header" style="margin-bottom: 0.5rem;">{{ $product->name }}</h1>
            <h3 class="ui header" style="margin: 5px 0;">{{ $product->summary }}</h3>
            <div class="ui divider"></div>
            <label>作者：梁伦忠</label>
            <label>出版时间：2017/5/1</label>
            <label>评分9.8</label>
            <label>分享</label>
            <div class="ui basic segment" style="margin-top: 0;">
              <span class="ui ribbon label"><label style="color: red;margin: 0;font-size: 18px;">${{ $product->price }}</label>
              </span>
              <br>
              <br>
              <label>促销：</label><span>促销促销促销促销促销</span>
              <br>
              <br>
              <label>其他：</label><span>其他其他其他其他其他其他其他</span>
            </div>
            <div class="ui basic segment">
              <a style="cursor: pointer;" class="minus-qty"><i class="large minus square outline icon" style="margin-right: -5px;"></i></a>
              <div class="ui input">
                <input type="text" value="1" style="width: 50px;height: 28px;" id="qty" onchange="changeQty(this);">
              </div>
              <a style="cursor: pointer;" class="plus-qty"><i class="large plus square outline icon" style="margin-left: -5px;"></i></a>
              @if ($count)
                <div class="ui teal  button" onclick="_toCart();">
                  <i class="shop icon"></i><span>查看购物车({{ $count }})</span>
                </div>
              @else
                <div class="ui teal  button addCart" onclick="_addCart();">
                  <i class="shop icon"></i><span>加入购物车</span>
                </div>
              @endif 
              <div class="ui teal  button ">
                立即购买
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
   *添加到购物车按钮
   */
  function _addCart() {
    var product_id = {{ $product->id }};
    var qty = $('#qty').val();

    $.ajax({
      type: 'GET',
      url: '/service/cart/add/' + product_id+ '/' + qty,
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

          $('.addCart').attr('onclick', '_toCart()');
          $('.addCart').find('span').html('查看购物车('+qty+')');
          // var num = $('#cart_num').html();
          // if(num == '') {
          //   num = 0;
          // }
          // num = Number(num) + Number(qty);
          // $('#cart_num').html(num);
      },
      error: function(xhr, status, error) {
          console.log(xhr);
          console.log(status);
          console.log(error);
      }
    });
  }

  /*
   *
   */
  function _toCart() {
    window.location.href = '/cart';
  }

  /*
   *手动输入改变数量验证
   */
   function changeQty(obj) {
    var qty = obj.value;

    if(qty == '' || isNaN(qty) || qty < 1) {
      obj.value = 1;
    } else {
      obj.value = parseInt(qty);
    }
  }


  /*
   *加数量按钮
   */
  $('.plus-qty').click(function() {
    var qty = $('#qty').val();
    if(qty == ''){
      qty = 0;
    }
    qty = Number(qty) + 1;
    $('#qty').val(qty);
  });

  /*
   *减数量按钮
   */
  $('.minus-qty').click(function() {
    var qty = $('#qty').val();
    if(qty == '') {
      qty = 0;
    }
    if(qty <= 1) {
      $('#qty').val(1);
    } else {
      qty = Number(qty) - 1;
      $('#qty').val(qty);
    }
  });

</script>


@endsection
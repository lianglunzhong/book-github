@extends('master')

@section('title','订单历史')

@section('crumb')

<div class="ui segment" style="border:0;box-shadow: 0 0 0 0;margin-bottom: 0px;">
  <div class="ui container">
    <div class="ui small breadcrumb" style="background-color: #fff;">
      <a class="section" href="/">首页</a>
      <div class="divider"> / </div>
      <a class="active section" href="javascript:void();" style="cursor: default;">我的订单</a>
    </div>
    <div class="ui divider" style="margin-top: 1px;"></div>
  </div>
</div>

@endsection

@section('content')
<div class="ui segment" style="border:0;box-shadow: 0 0 0 0;margin-top: 0px;">
    <div class="ui container">
        <div class="ui grid">
          <div class="row">
            <div class="three wide column">
                <div class="ui styled accordion" style="box-shadow: 0 0 0 0;">
                  <div class="active title" style="border-top: none;">
                    <i class="dropdown icon"></i>订单管理
                  </div>
                  <div class="active content" style="padding-left:45px;">
                    <div class="ui list">
                      <a href="" class="item acolor">
                        所有订单<div class="ui divider" style="width: 50%;"></div>
                      </a>
                      <a href="javascript:void(0);" class="item">
                        待付款<div class="ui divider" style="width: 50%;"></div>
                      </a>
                      <a href="javascript:void(0);" class="item">
                        待发货<div class="ui divider" style="width: 50%;"></div>
                      </a>
                      <a href="" class="item">
                        待收货<div class="ui divider" style="width: 50%;"></div>
                      </a>
                      <a href="" class="item">
                        待评价<div class="ui divider" style="width: 50%;"></div>
                      </a>
                    </div>
                  </div>
                  <div class="title" style="border-top: none;">
                    <i class="dropdown icon"></i>账号信息
                  </div>
                  <div class="content" style="padding-left:45px;">
                    <div class="ui list">
                      <a class="item">
                        账号设置<div class="ui divider" style="width: 50%;"></div>
                      </a>
                      <a class="item">
                        修改密码<div class="ui divider" style="width: 50%;"></div>
                      </a>
                      <a class="item">
                        地址管理<div class="ui divider" style="width: 50%;"></div>
                      </a>
                    </div>
                  </div>
                  <div class="title" style="border-top: none;">
                    <i class="dropdown icon"></i>我的收藏
                  </div>
                  <div class="content" style="padding-left:45px;">
                    <div class="ui list">
                      <a class="item">
                        收藏宝贝<div class="ui divider" style="width: 50%;"></div>
                      </a>
                      <a class="item">
                        我的足迹<div class="ui divider" style="width: 50%;"></div>
                      </a>
                    </div>
                  </div>
                </div>
            </div>
            <div class="thirteen wide column">
              <table class="ui table" style="background-color: #dcdcdc;border-radius: 0;">
                <tr>
                  <th style="width: 50%;text-align: center;">商品详情</th>
                  <th style="width: 10%;text-align: center;">单价</th>
                  <th style="width: 10%;text-align: center;">数量</th>
                  <th style="width: 15%;text-align: center;">总价</th>
                  <th style="width: 15%;text-align: center;">操作</th>
                </tr>
              </table>
              @foreach ($orders as $order)
              <table class="ui table" style="border-radius: 0;">
                <tr style="background-color: #f5f5f5;width: 100%;">
                  <td style="padding-left: 15px;"><b>{{ $order->created_at }}</b>&nbsp;&nbsp;&nbsp;&nbsp;订单号: {{ $order->order_no }}</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td style="width: 50%;vertical-align: middle;">
                    <div class="ui grid">
                            <div class="row" style="padding-left: 20px;">
                                <div class="four wide column">
                                    <a href="">
                                        <img style="width: 70px;" src="/images/book.jpg">
                                    </a>
                                </div>
                                <div class="twelve wide column" style="padding-top: 12px;">
                                    <a href="" style="color:#1b1c1d;">
                                      <h3>Laravel 5.1 Beauty 1</h3>
                                      <p>Creating Beautiful Web Apps in Laravel 5.1</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                  </td>
                  <td style="width: 10%;text-align: center;vertical-align: middle;">$18.88</td>
                  <td style="width: 10%;text-align: center;vertical-align: middle;">2</td>
                  <td style="width: 15%;text-align: center;vertical-align: middle;border-left: 1px solid rgba(34, 36, 38, 0.15)"><b>$36.34</b></td>
                  <td style="width: 15%;text-align: center;vertical-align: middle;border-left: 1px solid rgba(34, 36, 38, 0.15)"><a style="color:#1b1c1d;cursor: pointer;">查看详情</a></td>
                </tr>
              </table>
              @endforeach
            </div>
          </div>
        </div>
    </div>
</div>


@endsection

@section('my-js')

<script type="text/javascript">
    $('.ui.accordion')
  .accordion()
;
</script>

<script type="text/javascript">
  $('.ui.list a').click(function() {
    $(this).addClass('acolor').siblings().removeClass('acolor');
  });
</script>

@endsection
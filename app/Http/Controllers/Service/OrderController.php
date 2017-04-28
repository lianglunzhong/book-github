<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Models\M3Result;

class OrderController extends Controller
{
    //生成订单
    public function toOrderCommit(Request $request)
    {
        $m3_result = new M3Result;
        $payway = $request->input('payway');
        $total_price = $request->input('total_price');
        $product_ids = $request->input('product_ids');

        $product_ids_arr = ($product_ids != null ? explode(',', $product_ids) : array());

        $member = $request->session()->get('member', '');


        $order = new Order;
        $order->total_price = $total_price;
        $order->member_id = $member->id;
        $order->total_price = $total_price;
        $order->status = 1;
        $order->payway = $payway;
        $order->save();
        $order->order_no = 'Llz'.$order->id.'622848';
        $order->save();

        $cart_items = CartItem::where('member_id', $member->id)
                                ->whereIn('product_id', $product_ids_arr)->get();

        // $name = '';
        foreach ($cart_items as  $cart_item) {
            $cart_item->product = Product::find($cart_item->product_id);
            // $name .= ('《'.$cart_item->product->name.'》');
            $order_item = new OrderItem;
            $order_item->order_id = $order->id;
            $order_item->product_id = $cart_item->product_id;
            $order_item->count = $cart_item->count;
            $order_item->snapshot = json_encode($cart_item->product);
            $order_item->save();
            $cart_item->delete();
        }

        $m3_result->status = 0;
        $m3_result->message = '订单创建成功';

        return $m3_result->toJson();


    }
}

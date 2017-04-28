<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderItem;

class OrderController extends Controller
{
    public function toOrderCheckout(Request $request)
    {
        $product_ids = $request->input('product_ids', '');
        $product_ids_arr = ($product_ids != null ? explode(',', $product_ids) : array());

        $member = $request->session()->get('member', '');
        $cart_items = CartItem::where('member_id', '=', $member->id)
                                ->whereIN('product_id', $product_ids_arr)
                                ->get();

        $cart_items_arr = array();
        $p_price = 0;
        $s_price = 0;
        $total_price = 0;
        foreach($cart_items as $cart_item) {
            $cart_item->product = Product::find($cart_item->product_id);
            if($cart_item->product != null) {
                $p_price += $cart_item->product->price * $cart_item->count;
                $s_price += 1.00 * 1;
                array_push($cart_items_arr, $cart_item);
            }
        }

        $p_price = round($p_price, 2);
        $s_price = round($s_price, 2);
        $total_price =  round(($p_price + $s_price), 2);

        return view('order_checkout')->with('cart_items', $cart_items_arr)
                                    ->with('total_price', $total_price)
                                    ->with('p_price', $p_price)
                                    ->with('s_price', $s_price)
                                    ->with('product_ids', $product_ids);
    }


    public function toOrderList(Request $request)
    {
        $member = $request->session()->get('member');

        $orders = Order::where('member_id', $member->id)->get();
        foreach($orders as $order) {
            $order_items = OrderItem::where('order_id', $order->id)->get();
            $order->order_items = $order_items;
            foreach ($order_items as $order_item) {
                $order_item->product = Product::find($order_item->product_id);
            }
        }

        return view('order_list')->with('orders', $orders);
    }
}

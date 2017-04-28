<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\M3Result;
use App\Entity\CartItem;

class CartController extends Controller
{
    public function addCart(Request $request, $product_id, $qty)
    {
        $bk_cart = $request->cookie('bk_cart');
        // return $bk_cart;
        $bk_cart_arr = ($bk_cart != null ? explode(',', $bk_cart) : array());

        // $count = 1;
        $count = (int)$qty;
        foreach ($bk_cart_arr as &$value) {  //非对象，在循环中改变其值，需要传引用
            $index = strpos($value, ':');

            if(substr($value, 0, $index) == $product_id) {
                $count = ((int)substr($value, $index + 1)) + $qty;
                $value = $product_id . ':' . $count;
                break;
            }
        }

        if($count == $qty) {
            array_push($bk_cart_arr, $product_id . ':' . $count);
        }

        $bk_cart_str = implode(',', $bk_cart_arr);

        $m3_result = new M3Result;
        $m3_result->status = 0;
        $m3_result->message = '添加成功';

        return response($m3_result->toJson())->withCookie('bk_cart', $bk_cart_str);
    }

    public function deleteAllCart(Request $requset)
    {
        $m3_result = new M3Result;

        $product_ids = $requset->input('product_ids');
        if($product_ids == '') {
            $m3_result->status = 1;
            $m3_result->message = '请选择需要删除的产品';
            return $m3_result->toJson();
        }

        $product_ids_arr = explode(',', $product_ids);

        $bk_cart = $requset->cookie('bk_cart');
        $bk_cart_arr = ($bk_cart != null ? explode(',', $bk_cart) : array());
        
        foreach ($bk_cart_arr as $key => $value) {
            $index = strpos($value, ':');
            $product_id = substr($value, 0, $index);

            #存在，删除
            if(in_array($product_id, $product_ids_arr)) {
                array_splice($bk_cart_arr, $key, 1);
                continue;
            }
        }

        $bk_cart_str = implode(',', $bk_cart_arr);

        $m3_result->status = 0;
        $m3_result->message = '删除成功';
        return response($m3_result->toJson())->withCookie('bk_cart', $bk_cart_str);

    }


    public function deleteOneCart(Request $request, $product_id)
    {
        $m3_result = new M3Result;

        $pid = (int)$product_id;

        //判断是否有登录，在登录的情况下删除直接删数据库中的记录
        $member = $request->session()->get('member', '');
        if($member != '') {
            $cart_item = CartItem::where('product_id', '=', $pid)
                                    ->where('member_id', '=', $member->id)
                                    ->delete();
            $m3_result->status = 0;
            $m3_result->message = '删除成功';
            return $m3_result->toJson();
        }

        $bk_cart = $request->cookie('bk_cart');
        $bk_cart_arr = ($bk_cart != null ? explode(',', $bk_cart) : array());

        foreach ($bk_cart_arr as $key => $value) {
            $index = strpos($value, ':');
            $product_id = substr($value, 0, $index);

            #存在，删除
            if((int)$product_id == $pid) {
                array_splice($bk_cart_arr, $key, 1);
                break;
            }
        }

        $bk_cart_str = implode(',', $bk_cart_arr);

        $m3_result->status = 0;
        $m3_result->message = '删除成功';
        return response($m3_result->toJson())->withCookie('bk_cart', $bk_cart_str);
    }
}

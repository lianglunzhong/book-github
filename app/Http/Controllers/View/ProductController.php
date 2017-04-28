<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entity\Product;
use App\Entity\CartItem;

class ProductController extends Controller
{
    //产品详情页
    public function toPdtContent(Request $request, $product_id)
    {	
    	$bk_cart = $request->cookie('bk_cart');
        // return $bk_cart;
        $bk_cart_arr = ($bk_cart != null ? explode(',', $bk_cart) : array());

        $count = 0;
        foreach ($bk_cart_arr as $value) {
            $index = strpos($value, ':');
            if(substr($value, 0, $index) == $product_id) {
                $count = ((int)substr($value, $index + 1));
                break;
            }
        }

        $product = Product::find($product_id);
    	
    	return view('product')->with('product', $product)
    						  ->with('count', $count);
    }

}

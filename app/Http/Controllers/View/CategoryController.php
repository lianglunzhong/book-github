<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entity\Category;
use App\Entity\Product;
use Log;
use DB;

class CategoryController extends Controller
{
    //书籍分类列表
    public function toCategory()
    {
        $categorys = Category::whereNull('parent_id')->first()->get();
        return view('category')->with('categorys', $categorys);
    }


    //分类产品页
    public function toProduct(Request $requset, $category_id)
    {
        // Log::info('进入分类产品页');
        //获取分页
        $page = $requset->input('page', 1);
        $product_start = ((int)$page - 1) * 6;
        $limit_sql = ' limit ' . $product_start . ',6';

        $category = Category::where('id', '=', $category_id)->first();

        // $products = Product::where('category_id', '=', $category_id)
        //                     ->orderBy('id','esc')
        //                     ->take(9)
        //                     ->get();
        $products = DB::select('select * from product where category_id = ' . $category_id . $limit_sql);

        return view('category')->with('products', $products)
                               ->with('category', $category)
                               ->with('page', $page);
    }

}

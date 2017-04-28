<?php

namespace App\Http\Controllers\Admin\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entity\Category;
use App\Models\M3Result;

class CategoryController extends Controller
{
    public function categoryAdd(Request $requset)
    {
        $m3_result = new M3Result;

        $name = $requset->input('name', '');
        $category_no = $requset->input('category_no', '');
        $parent_id = $requset->input('parent_id', '');
        $preview = $requset->input('preview', '');

        $category = new Category;
        $category->name = $name;
        $category->category_no = $category_no;
        $category->preview = $preview;
        if($parent_id != '') {
            $category->parent_id = $parent_id;
        }

        $category->save();

        $m3_result->status = 0;
        $m3_result->message = '添加成功';

        return $m3_result->toJson();
    }


    public function categoryDelete(Request $requset)
    {
        $m3_result = new M3Result;

        $category_id = $requset->input('category_id','');
        Category::find($category_id)->delete();

        $m3_result->status = 0;
        $m3_result->message = '删除成功';

        return $m3_result->toJson();
    }


    public function categoryEdit(Request $requset)
    {
        $m3_result = new M3Result;

        $id = $requset->input('id', '');
        $name = $requset->input('name', '');
        $category_no = $requset->input('category_no', '');
        $parent_id = $requset->input('parent_id', '');
        $preview = $requset->input('preview', '');

        $category = Category::find($id);
        $category->name = $name;
        $category->category_no = $category_no;
        $category->preview = $preview;
        if($parent_id != '') {
            $category->parent_id = $parent_id;
        }

        $category->save();

        $m3_result->status = 0;
        $m3_result->message = '添加成功';

        return $m3_result->toJson();
    }
}

<?php

namespace App\Http\Controllers\Admin\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entity\Category;

class CategoryController extends Controller
{
    public function toCategory(Request $request)
    {
        $categorys = Category::all();
        foreach($categorys as $category) {
            if($category->parent_id != null && $category->parent_id != '') {
                $category->parent = Category::find($category->parent_id);
            }
        }

        return view('admin.category')->with('categorys', $categorys);
    }


    public function toCategoryAdd(Request $request)
    {
        $categorys = Category::whereNull('parent_id')->get();

        return view('admin.category_add')->with('categorys', $categorys);
    }


    public function toCategoryEdit(Request $request, $id)
    {
        $category = Category::find($id);
        $categorys = Category::whereNull('parent_id')->get();

        return view('admin.category_edit')->with('categorys', $categorys)->with('category', $category);
    }
}

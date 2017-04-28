<?php

namespace App\Http\Controllers\Admin\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
	public function index(Request $request)
	{
		return view('admin.index');
	}

    public function toLogin(Request $request)
    {
    	return view('admin.login');
    }
}

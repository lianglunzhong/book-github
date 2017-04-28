<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    //登录页面
    public function toLogin(Request $requset)
    {
        $return_url = $requset->input('return_url', '');
        $return_url = urldecode($return_url);
        
        return view('login')->with('return_url', $return_url);
    }

    //注册页面
    public function toRegister(Request $requset)
    {
        return view('register');
    }
}

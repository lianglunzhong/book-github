<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\M3Result;
use App\Models\M3Email;
use App\Entity\TempPhone;
use App\Entity\Member;
use App\Entity\TempEmail;
use Mail;
use App\Tool\UUID;

class MemberController extends Controller
{   
    /*
     *用户注册
     */
    public function register(Request $requset)
    {
        $email = $requset->input('email', '');
        $phone = $requset->input('phone', '');
        $password = $requset->input('password', '');
        $confirm = $requset->input('confirm', '');
        $phone_code = $requset->input('phone_code', '');
        $validate_code = $requset->input('validate_code', '');

        $m3_result = new M3Result;

        if($email == '' && $phone == '') {
            $m3_result->status = 1;
            $m3_result->message = '手机号或邮箱不能为空';
            return $m3_result->toJson();
        }

        if($password == '' || strlen($password) < 6) {
          $m3_result->status = 2;
          $m3_result->message = '密码不少于6位';
          return $m3_result->toJson();
        }

        if($confirm == '' || strlen($confirm) < 6) {
          $m3_result->status = 3;
          $m3_result->message = '确认密码不少于6位';
          return $m3_result->toJson();
        }

        if($password != $confirm) {
          $m3_result->status = 4;
          $m3_result->message = '两次密码不相同';
          return $m3_result->toJson();
        }

        // 手机号注册
        if($phone != '') {
            if($phone_code == '' || strlen($phone_code) != 6) {
                $m3_result->status = 5;
                $m3_result->message = '手机验证码为6位';
                return $m3_result->toJson();
            }

            //判断手机验证码是否正确
            $tempPhone = TempPhone::where('phone', '=', $phone)->orderBy('id', 'desc')->first();

            if($tempPhone->code == $phone_code) {
                if(time() > strtotime($tempPhone->deadline)) {
                    $m3_result->status = 6;
                    $m3_result->message = '手机验证码不正确';
                    return $m3_result->toJson();
                }

                //保存用户
                $member = new Member;
                $member->phone = $phone;
                $member->password = md5('bk' + $password);
                $member->active = 1;
                $member->save();

                $m3_result->status = 0;
                $m3_result->message = '注册成功';
                return $m3_result->toJson();
            } else {
                $m3_result->status = 7;
                $m3_result->message = '手机验证码不正确';
                return $m3_result->toJson();
            }
        } else {
            //邮箱注册
            if($validate_code == '' || strlen($validate_code) != 4) {
                $m3_result->status = 5;
                $m3_result->message = '验证码为4位';
                return $m3_result->toJson();
            }

            $validate_code_session = $requset->session()->get('validate_code', '');
            if($validate_code_session != $validate_code) {
                $m3_result->status = 8;
                $m3_result->message = '验证码不正确';
                return $m3_result->toJson();
            }

            //保存用户
            $member = new Member;
            $member->email = $email;
            $member->password = md5('bk' + $password);
            $member->save();

            $uuid = UUID::create();

            //验证邮件及内容
            $m3_email = new M3Email;
            $m3_email->to = $email;
            $m3_email->cc = '744715059@qq.com';
            $m3_email->subject = '小可的店';
            $m3_email->content = '请于24小时点击该链接完成验证. http://local.book.com/service/validate_email'
                                . '?member_id=' . $member->id 
                                . '&code=' . $uuid;

            #保存验证邮件信息
            $tempEmail = new TempEmail;
            $tempEmail->member_id = $member->id;
            $tempEmail->code = $uuid;
            $tempEmail->deadline = date('Y-m-d H:i:s', time() + 24*60*60);
            $tempEmail->save();

            #发送邮件
            Mail::send('email_register', ['m3_email' => $m3_email], function ($m) use ($m3_email) {
                // $m->from('hello@app.com', 'Your Application')
                $m->to($m3_email->to, '尊敬的用户')
                  ->cc($m3_email->cc)
                  ->subject($m3_email->subject);
            });

            $m3_result->status = 0;
            $m3_result->message = '注册成功';
            return $m3_result->toJson();
        }

        return $m3_result->toJson();
    }


    /*
     *用户登录
     */
    public function login(Request $requset)
    {

        //获取用户输入信息
        $username = $requset->input('username', '');
        $password = $requset->input('password', '');
        $validate_code = $requset->input('validate_code', '');

        $m3_result = new M3Result;

        //校验

        //验证
        $validate_code_session = $requset->session()->get('validate_code', '');
        if($validate_code_session != $validate_code) {
            $m3_result->status = 1;
            $m3_result->message = '验证码不正确';
            return $m3_result->toJson();
        }

        //判断登陆方式
        $member = null;
        if(strpos($username, '@') == true) {
            $member = Member::where('email', '=', $username)->first();
        } else {
            $member = Member::where('phone', '=', $username)->first();
        }

        //验证账号
        if($member == null) {
            $m3_result->status = 2;
            $m3_result->message = '该用户不存在';
            return $m3_result->toJson();
        } else {
            //验证密码
            if(md5('bk' + $password) != $member->password) {
                $m3_result->status = 3;
                $m3_result->message = '密码不正确';
                return $m3_result->toJson();
            }

            $requset->session()->put('member', $member);

            $m3_result->status = 0;
            $m3_result->message = '登录成功';
            return $m3_result->toJson();
        }
    }
}

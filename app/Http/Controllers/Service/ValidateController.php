<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tool\Validate\ValidateCode;
use App\Tool\SMS\sendTemplateSMS;
use App\Entity\TempPhone;
use App\Entity\TempEmail;
use App\Models\M3Result;
use App\Entity\Member;

class ValidateController extends Controller
{   
    //邮箱注册生成验证码
    public function create(Request $requset)
    {
        $validateCode = new ValidateCode;
        $requset->session()->put('validate_code', $validateCode->getCode());
        return $validateCode->doimg();
    }

    //手机注册发送短信验证码
    public function sendSMS(Request $requset)
    {
        $m3_result = new M3Result;

        $phone = $requset->input('phone', '');
        if($phone == '')
        {
            $m3_result->status = 1;
            $m3_result->message = '手机号不能为空';
            return $m3_result->toJson();
        }

        $sendTemplateSMS = new sendTemplateSMS;

        $code = '';
        $charset = '1234567890';
        $_len = strlen($charset) - 1;
        for($i = 0;$i < 6;$i++)
        {
            $code .= $charset[mt_rand(0, $_len)];
        }

        // $sendTemplateSMS->sendTemplateSMS("17000204093", array($code, 60), 1);
        $m3_result = $sendTemplateSMS->sendTemplateSMS($phone, array($code, 60), 1);

        if($m3_result->status == 0) {
            $tempPhone = new TempPhone;
            $tempPhone->phone = $phone;
            $tempPhone->code = $code;
            $tempPhone->deadline = date('Y-m-d H:i:s', time() + 60*60);
            $tempPhone->save();
        }
        
        return $m3_result->toJson();
    }

    //邮箱注册：邮箱链接认证
    public function validateEmail(Request $requset)
    {
        $member_id = $requset->input('member_id', '');
        $code = $requset->input('code', '');

        if($member_id == '' || $code == '') {
            return '验证异常1';
        }

        $tempEmail = TempEmail::where('member_id', '=', $member_id)->first();

        if($tempEmail == null) {
            return '验证异常2';
        }

        if($tempEmail->code == $code){
            if(time() > strtotime($tempEmail->deadline)) {
                return '该链接已失效';
            }

            $member = Member::find($member_id);
            $member->active = 1;
            $member->save();

            return redirect('/');
        } else {
            return '该链接已失效';
        }
    }
}

<?php

namespace App\Http\Controllers\Admin\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\M3Result;
use App\Tool\UUID;

class UploadFileController extends Controller
{
    public function uploadFile(Request $request, $type)
    {
        $m3_result = new M3Result;

        $width = $request->input('width', '');
        $height = $request->input('height', '');

        $file = $_FILES['file'];

        if($file['error'] >0) {
            $m3_result->status = 2;
            $m3_result->message = "未知错误, 错误码: " . $_FILES['file']['error'];
            return $m3_result->toJson();
        }

        $file_size = $file['size'];
        if($file_size > 2*1024*1024) {
            $m3_result->status = 2;
            $m3_result->message = "请注意图片上传大小不能超过2M";
            return $m3_result->toJson();
        }

        //配置上传的文件保存路径
        $public_dir = sprintf('/upload/%s/%s/', $type, date('Ymd'));
        $upload_dir = public_path() . $public_dir;
        if(!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        //获取文件名
        $file_name = $file['name'];
        $upload_filename = time() . $file_name;
        $upload_file_path = $upload_dir . $upload_filename;

        if(strlen($width) > 0) {
            $public_uri = $public_dir . $upload_filename;
            $m3_result->status = 0;
            $m3_result->message = "上传成功";
            $m3_result->uri = $public_uri;
            return $m3_result->toJson();
        } else {
            //从临时目标移到上传目录
            if(move_uploaded_file($file['tmp_name'], $upload_file_path)) {
                $public_uri = $public_dir . $upload_filename;

                $m3_result->status = 0;
                $m3_result->message = "上传成功";
                $m3_result->uri = $public_uri;
                return $m3_result->toJson();
            } else {
                $m3_result->status = 1;
                $m3_result->message = "上传失败";
                return $m3_result->toJson();
            }
        }
    }
}

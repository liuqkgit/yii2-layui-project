<?php

namespace common\models;

class ReturnMsg
{

    public static function ReturnMsg($bool = true, $array = [])
    {
        if ($bool) {
            $onBoolMsg = 'success';
            $msg = '请求执行成功';
            $res_data = [];
        } else {
            $onBoolMsg  = 'fail';
            $msg = '请求执行失败';
            $res_data = [];
        }

        $data_code = isset($array['code']) ? $array['code'] : false;
        $data_msg = isset($array['msg']) ? $array['msg'] : false;
        $data_data = isset($array['data']) ? $array['data'] : false;

        return json_encode([
            'request' => 'success',
            'code' => $data_code ?: $onBoolMsg,
            'msg' => $data_msg ?: $msg,
            'data' => $data_data ?: $res_data
        ]);
    }

    //针对layui 表格的统一返回
    public static function LayuiReturn($bool = true, $data = [])
    {
        $code = 200;

        $count = 0;

        $msg = '请求执行成功';

        $res_data = [];

        if (!$bool) {

            $code  = 400;

            $msg = '请求执行失败';

            $res_data = [];
        }

        return json_encode(
            [
                'request' => 'success',

                'code' => isset($data['code']) ? ($data['code'] ?: $code)  : $code,

                'count' => isset($data['count']) ? ($data['count'] ?: $count)  : $count,

                'msg' => isset($data['msg']) ? ($data['msg'] ?: $msg)  : $msg,

                'data' => isset($data['data']) ? ($data['data'] ?: $res_data)  : $res_data
            ]
        );
    }



    //模拟请求
    public static function SendData($url, $type = 'get', $postData = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); //要访问地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if ($type == "post") {
            curl_setopt($ch, CURLOPT_POST, 1); //设置post;
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }

        $info = curl_exec($ch);
        curl_close($ch);
        return $info;
    }
}

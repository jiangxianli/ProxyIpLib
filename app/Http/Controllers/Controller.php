<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    /**
     * JSON 格式化响应
     *
     * @param $data
     * @return array
     * @author jiangxianli
     * @created_at 2017-12-25 15:01:27
     */
    public function jsonFormat($data)
    {
        if (is_object($data)) {
            if (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            }
        }

        if (!is_array($data)) {
            $data = (array)$data;
        }

        //认为接口返回的
        if (isset($data['code']) && isset($data['msg']) && isset($data['module'])) {
            return $data;
        }

        return [
            'code' => 0,
            'msg'  => '成功',
            'data' => $data,
        ];
    }
}

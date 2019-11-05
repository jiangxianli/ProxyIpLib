<?php

namespace App\Exceptions;

use Exception;

class JsonException extends Exception
{
    /**
     * 自定义错误
     *
     * @var array
     */
    private $codes = [

        '10000' => [
            'msg' => '参数错误'
        ],

        '20000' => [
            'msg' => '抓取返回内容错误!'
        ],
        '20001' => [
            'msg' => '响应时间过长!'
        ],

        '90000' => [
            'msg' => '接口返回错误!'
        ]

    ];

    /**
     * 响应数据
     *
     * @var array
     */
    private $data = [];

    /**
     * 错误代码
     *
     * @var string
     */
    protected $code;

    /**
     * 构造函数
     *
     * JsonException constructor.
     * @param string $code
     * @param array $data
     */
    public function __construct($code, $data = [])
    {
        $this->code = $code;
        $this->data = $data;
    }

    /**
     * 错误格式化
     *
     * @return array
     * @author jiangxianli
     * @created_at 2017-12-22 16:06:36
     */
    public function formatError()
    {
        //获取错误Code
        $code = isset($this->codes[$this->code]) ? $this->code : 10000;
        //错误消息
        $msg = $this->codes[$code]['msg'];
        //响应数据
        $data = $this->data;

        return [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ];
    }
}

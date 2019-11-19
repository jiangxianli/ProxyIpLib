<?php

namespace App\Http\Business\Dao;

use App\Exceptions\JsonException;

class BlogDao
{
    /**
     *
     * ProxyIpDao constructor.
     */
    public function __construct()
    {

    }

    /**
     * 获取文章列表
     *
     * @param array $condition
     * @param array $columns
     * @param array $relatives
     * @return mixed
     * @author jiangxianli
     * @created_at 2017-12-25 10:33:56
     */
    public function getBlogList($condition = [], $columns = ['*'], $relatives = [])
    {
        $proxy_ip = app('BlogModel')->select($columns);

        //
        if (isset($condition['order_by']) && isset($condition['order_rule'])) {
            $proxy_ip->orderBy($condition['order_by'], strtoupper($condition['order_rule']) == "DESC" ? 'desc' : 'asc');
        }

        if (isset($condition['all']) && $condition['all'] == 'true') {
            $proxy_ip = $proxy_ip->get();
        } else if (isset($condition['first']) && $condition['first'] == 'true') {
            $proxy_ip = $proxy_ip->first();
        } else {
            $page_size = array_get($condition, 'page_size', 15);
            $proxy_ip = $proxy_ip->paginate($page_size);
        }

        if (!empty($relatives)) {
            $proxy_ip = $proxy_ip->load($relatives);
        }

        return $proxy_ip;
    }

    /**
     * 添加数据
     *
     * @param array $data
     * @return \Laravel\Lumen\Application|mixed
     * @throws JsonException
     * @author jiangxianli
     * @created_at 2017-12-22 16:15:18
     */
    public function addBlog(array $data = [])
    {
        $rule = [
            'date_time' => ['required'],
            'content'   => ['required'],
        ];
        $validator = app('validator')->make($data, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }

        $blog = app('BlogModel')->firstOrCreate([
            'date_time' => $data['date_time']
        ]);
        $blog->fill($data);
        $blog->save();

        return $blog;
    }
}
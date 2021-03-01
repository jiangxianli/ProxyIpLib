<?php

namespace App\Http\Business\Dao;

use App\Exceptions\JsonException;

class AdDao
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
    public function getAdList($condition = [], $columns = ['*'], $relatives = [])
    {
        $model = app('AdModel')->select($columns);

        if (!empty($condition['id'])) {
            $model->where('id', $condition['id']);
        }
        //
        if (!empty($condition['area'])) {
            $model->where('area', $condition['area']);
        }
        if (!empty($condition['is_show'])) {
            $model->where('is_show', $condition['is_show']);
        }
        if (isset($condition['order_by']) && isset($condition['order_rule'])) {
            $model->orderBy($condition['order_by'], strtoupper($condition['order_rule']) == "DESC" ? 'desc' : 'asc');
        }

        if (isset($condition['all']) && $condition['all'] == 'true') {
            $model = $model->get();
        } else if (isset($condition['first']) && $condition['first'] == 'true') {
            $model = $model->first();
        } else if (!empty($condition['limit'])) {
            $model = $model->limit($condition['limit'])->get();
        } else {
            $page_size = array_get($condition, 'page_size', 15);
            $model = $model->paginate($page_size);
        }

        if (!empty($relatives)) {
            $model = $model->load($relatives);
        }

        return $model;
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
    public function addAd(array $data = [])
    {
        $rule = [
            'area'       => ['required'],
            'ad_content' => ['required'],
        ];
        $validator = app('validator')->make($data, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }

        $model = app('BlogModel');
        $model->fill($data);
        $model->save();

        return $model;
    }
}
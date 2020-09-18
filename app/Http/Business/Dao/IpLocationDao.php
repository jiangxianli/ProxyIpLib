<?php

namespace App\Http\Business\Dao;

use App\Exceptions\JsonException;

class IpLocationDao
{
    /**
     *
     * ProxyIpDao constructor.
     */
    public function __construct()
    {
    }

    /**
     * 获取IP定位列表
     *
     * @param array $condition
     * @param array $columns
     * @param array $relatives
     * @return mixed
     * @author jiangxianli
     * @created_at 2017-12-25 10:33:56
     */
    public function getIpLocationList($condition = [], $columns = ['*'], $relatives = [])
    {
        $model = app('IpLocationModel')->select($columns);

        if (!empty($condition['ip'])) {
            $model->where('ip', $condition['ip']);
        }
        if (!empty($condition['ip_number'])) {
            $model->where('ip_number', $condition['ip_number']);
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
    public function addIpLocation(array $data = [])
    {
        $rule = [
            'ip'        => ['required'],
            'ip_number' => ['required'],
            'country'   => ['required'],
            'isp'       => ['required'],
        ];
        $validator = app('validator')->make($data, $rule);
        if ($validator->fails()) {
            throw new JsonException(10000, $validator->messages());
        }

        $model = app('IpLocationModel')->firstOrCreate([
            'ip_number' => $data['ip_number']
        ]);
        $model->fill($data);
        $model->save();

        return $model;
    }
}
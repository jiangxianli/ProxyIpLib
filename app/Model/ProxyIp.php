<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProxyIp extends Model
{
    /**
     * 表名
     *
     * @var string
     */
    protected $table = 'proxy_ips';

    /**
     * 可填充字段
     *
     * @var array
     */
    protected $fillable = [
        'unique_id',
        'country',
        'ip_address',
        'ip',
        'port',
        'anonymity',
        'speed',
        'isp',
        'validated_at',
    ];
}

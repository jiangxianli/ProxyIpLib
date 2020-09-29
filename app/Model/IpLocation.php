<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IpLocation extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ip_number';

    /**
     * 表名
     *
     * @var string
     */
    protected $table = 'ip_location';

    /**
     * 可填充字段
     *
     * @var array
     */
    protected $fillable = [
        'ip_number',
        'ip',
        'country',
        'region',
        'city',
        'isp',
    ];
}

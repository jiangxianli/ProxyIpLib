<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    /**
     * 表名
     *
     * @var string
     */
    protected $table = 'ads';

    /**
     * 可填充字段
     *
     * @var array
     */
    protected $fillable = [
        'area',
        'ad_content',
        'is_show'
    ];
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    /**
     * 表名
     *
     * @var string
     */
    protected $table = 'blog';

    /**
     * 可填充字段
     *
     * @var array
     */
    protected $fillable = [
        'date_time',
        'content',
    ];

    /**
     * 追加字段
     *
     * @var array
     */
    protected $appends = [
        'content_html'
    ];

    /*****************常用属性 start*********************/

    /**
     * 显示名称
     *
     * @return string
     * @author jiangxianli
     * @created_at 2019-08-28 16:26
     */
    public function getContentHtmlAttribute()
    {
        $arr = (array)json_decode($this->attributes['content'], true);

        return implode(" <br>", array_map(function ($item) {
            return sprintf(
                "%s:%s@%s#[%s] %s %s",
                $item['ip'],
                $item['port'],
                strtoupper($item['protocol']),
                $item['anonymity'] == 2 ? "高匿" : "透明",
                $item['ip_address'],
                $item['isp']
            );
        }, $arr));
    }
}

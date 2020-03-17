# 免费代理IP库

### 警告
代理IP采集于网络，仅供个人学习使用。请勿用于非法途径，违者后果自负！

### 获取一个验证通过的代理IP
* URL: https://ip.jiangxianli.com/api/proxy_ip
* 响应数据
```json
{
    "code":0,
    "msg":"成功",
    "data":{
        "unique_id":"ad0611edba534fd2c39a36d77e383cfb",
        "ip":"118.193.107.80",
        "port":"80",
        "ip_address":"北京市 北京市",
        "anonymity":0,
        "protocol":"http",
        "isp":"电信",
        "speed":375,
        "validated_at":"2017-12-25 14:38:25",
        "created_at":"2017-12-25 14:38:25",
        "updated_at":"2017-12-25 14:38:25"
    }
}
```

### 获取代理IP列表
* URL: https://ip.jiangxianli.com/api/proxy_ips
* 请求参数

| 参数名 | 数据类型 | 必传 | 说明 | 例子 |
| :---|:---| :---| :--- | :--- |
|page|int|N|第几页|1|
|country|string|N|所属国|中国,美国|
|isp|string|N|ISP|电信,阿里云|
|order_by|string|N|排序字段|speed:响应速度,validated_at:最新校验时间 created_at:存活时间|
|order_rule|string|N|排序方向|DESC:降序 ASC:升序|
* 响应数据
```json
{
    "code":0,
    "msg":"成功",
    "data":{
        "current_page":1,
        "data":[
            {
                "unique_id":"dd2aa4a97ab900ad5c7b679e445d9cde",
                "ip":"119.167.153.50",
                "port":"8118",
                "ip_address":"山东省 青岛市",
                "anonymity":0,
                "protocol":"http",
                "isp":"联通",
                "speed":46,
                "validated_at":"2017-12-25 15:11:05",
                "created_at":"2017-12-25 15:11:05",
                "updated_at":"2017-12-25 15:11:05"
            },
            {
                "unique_id":"7468e4ee73bf2be35b36221231ab02d5",
                "ip":"119.5.0.42",
                "port":"22",
                "ip_address":"四川省 南充市",
                "anonymity":0,
                "protocol":"http",
                "isp":"联通",
                "speed":127,
                "validated_at":"2017-12-25 15:10:04",
                "created_at":"2017-12-25 14:38:14",
                "updated_at":"2017-12-25 15:10:04"
            }
        ],
        "last_page":1,
        "per_page":15,
        "to":8,
        "total":8
    }
}
```

## 访问频率限制
请注意访问频率以及防止无效页的数据获取。多次违规操作访问将被限制IP访问。如需解除IP请在修正操作后，ISSUE中申请解除限制。

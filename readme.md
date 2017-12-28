# 免费代理IP库

### 警告
代理IP采集于网络，请勿用于非法途径，违者后果自负！

### 获取一个验证通过的代理IP
```shell
curl http://ip.jiangxianli.com/api/proxy_ip
```
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
```shell
curl http://ip.jiangxianli.com/api/proxy_ips
```
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
            },
            {
                "unique_id":"ff48808450a09d3c6bba79c5e666af1c",
                "ip":"121.232.145.120",
                "port":"9000",
                "ip_address":"江苏省 镇江市",
                "anonymity":0,
                "protocol":"http",
                "isp":"电信",
                "speed":367,
                "validated_at":"2017-12-25 15:10:35",
                "created_at":"2017-12-25 15:10:35",
                "updated_at":"2017-12-25 15:10:35"
            },
            {
                "unique_id":"1138678d206cf4ebb1642ad50fb812d6",
                "ip":"119.5.1.17",
                "port":"22",
                "ip_address":"四川省 南充市",
                "anonymity":0,
                "protocol":"http",
                "isp":"联通",
                "speed":371,
                "validated_at":"2017-12-25 15:10:04",
                "created_at":"2017-12-25 14:37:00",
                "updated_at":"2017-12-25 15:10:04"
            },
            {
                "unique_id":"a42f199a1eafc5fa8933d12c673d0004",
                "ip":"118.193.107.80",
                "port":"80",
                "ip_address":"北京市 北京市",
                "anonymity":0,
                "protocol":"http",
                "isp":"电信",
                "speed":376,
                "validated_at":"2017-12-25 15:11:05",
                "created_at":"2017-12-25 15:11:05",
                "updated_at":"2017-12-25 15:11:05"
            },
            {
                "unique_id":"64d321f9e61251fde6aa0a92bdbbdc62",
                "ip":"121.232.147.143",
                "port":"9000",
                "ip_address":"江苏省 镇江市",
                "anonymity":0,
                "protocol":"http",
                "isp":"电信",
                "speed":413,
                "validated_at":"2017-12-25 15:10:02",
                "created_at":"2017-12-25 14:33:15",
                "updated_at":"2017-12-25 15:10:02"
            },
            {
                "unique_id":"4fa2a5e616a6ae0dc7935fd5db02a314",
                "ip":"119.5.0.20",
                "port":"22",
                "ip_address":"四川省 南充市",
                "anonymity":0,
                "protocol":"http",
                "isp":"联通",
                "speed":571,
                "validated_at":"2017-12-25 15:10:03",
                "created_at":"2017-12-25 14:31:44",
                "updated_at":"2017-12-25 15:10:03"
            },
            {
                "unique_id":"499b0d5cc43fd3a82d1c1d63fccbc045",
                "ip":"180.118.128.247",
                "port":"9000",
                "ip_address":"江苏省 镇江市",
                "anonymity":0,
                "protocol":"http",
                "isp":"电信",
                "speed":1131,
                "validated_at":"2017-12-25 15:10:04",
                "created_at":"2017-12-25 14:32:15",
                "updated_at":"2017-12-25 15:10:04"
            }
        ],
        "first_page_url":"http://ip.jiangxianli.com/api/proxy_ips?page=1",
        "from":1,
        "last_page":1,
        "last_page_url":"http://ip.jiangxianli.com/api/proxy_ips?page=1",
        "next_page_url":null,
        "path":"http://ip.jiangxianli.com/api/proxy_ips",
        "per_page":15,
        "prev_page_url":null,
        "to":8,
        "total":8
    }
}
```
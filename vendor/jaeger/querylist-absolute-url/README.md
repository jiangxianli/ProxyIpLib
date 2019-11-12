# QueryList-AbsoluteUrl
QueryList Plugin: Converting relative urls to absolute.

QueryList插件:转换URL相对路径到绝对路径.

> QueryList:[https://github.com/jae-jae/QueryList](https://github.com/jae-jae/QueryList)

## Installation for QueryList4
```
composer require jaeger/querylist-absolute-url
```

## API
-  **absoluteUrl($baseUrl)**: Convert Page All Url to Absolute Url,return **QueryList**
-  **absoluteUrlHelper($baseUrl,$relativeUrl)**:  Convert Helper Function,return **string**

## Installation options

 **QueryList::use(AbsoluteUrl::class,$opt1,$opt2)**
- **$opt1**:`absoluteUrl` function alias.
- **$opt2**:`absoluteUrlHelper` function alias.

## Usage

- Installation Plugin

```php
use QL\QueryList;
use QL\Ext\AbsoluteUrl;

$ql = QueryList::getInstance();
$ql->use(AbsoluteUrl::class);
//or Custom function name
$ql->use(AbsoluteUrl::class,'absoluteUrl','absoluteUrlHelper');
```

- Convert All Link

```php
$data = $ql->get('https://toutiao.io/')
	->absoluteUrl('https://toutiao.io/')
    ->find('a')->attrs('href');
    
print_r($data);
```
Out:
```
Array
(
    [0] => https://toutiao.io/
    [1] => https://toutiao.io/explore
    [2] => https://toutiao.io/posts/hot/7
    [3] => https://toutiao.io/contribute
    [4] => https://toutiao.io/account/subscriptions
	//....
)
```

- Convert Helper

```php
$data = $ql->rules([
    'link' => ['a','href']
])->get('https://toutiao.io/')->query()->getData(function ($item) use($ql){
    $item['link'] = $ql->absoluteUrlHelper('https://toutiao.io/',$item['link']);
    return $item;
});

print_r($data);
```
Out:
```
Array
(
    [0] => Array
        (
            [link] => https://toutiao.io/
        )
    [1] => Array
        (
            [link] => https://toutiao.io/explore
        )
    [2] => Array
        (
            [link] => https://toutiao.io/posts/hot/7
        )
    [3] => Array
        (
            [link] => https://toutiao.io/contribute
        )
    [4] => Array
        (
            [link] => https://toutiao.io/account/subscriptions
        )
    //...
)
```

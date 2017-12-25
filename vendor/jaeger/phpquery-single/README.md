# phpQuery-single
phpQuery onefile composer.Continuous maintenance,Welcome PR.

`QueryList` base on phpQuery: https://github.com/jae-jae/QueryList

phpQuery单文件版本,持续维护，欢迎PR.
> phpQuery项目主页:http://code.google.com/p/phpquery/

`QueryList`是基于phpQuery的采集工具: https://github.com/jae-jae/QueryList

## Composer Installation
Packagist: https://packagist.org/packages/jaeger/phpquery-single
```
composer require jaeger/phpquery-single
```

## Usage
```php
$html = <<<STR
<div id="one">
    <div class="two">
        <a href="http://querylist.cc">QueryList官网</a>
        <img src="http://querylist.cc/1.jpg" alt="这是图片">
        <img src="http://querylist.cc/2.jpg" alt="这是图片2">
    </div>
    <span>其它的<b>一些</b>文本</span>
</div>        
STR;

$doc = phpQuery::newDocumentHTML($html);

$src = $doc->find('.two img:eq(0)')->attr('src');

echo $src;
// http://querylist.cc/1.jpg
```
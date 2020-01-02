<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>免费代理IP库</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="https://cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

    @include('layout.common_js')
</head>
<body>

<ul>
    <li><a href="{{route('web.index',[])}}">全球免费代理IP库</a></li>
    @foreach($countries as $item)
        <li><a href="{{ route("web.country",['area' => $item,'country' => $item]) }}">{{ $item }} 免费代理IP</a></li>
    @endforeach
    @foreach($isp as $item)
        <li><a href="{{ route('web.index',['isp' => $item]) }}">{{ $item }} 免费代理IP</a></li>
    @endforeach
    <li><a href="{{route('blog.index',[])}}">每小时热门IP</a></li>

    @foreach ($blogs as $blog)
        <li>
            <a href="{{ route('blog.detail',['blog_id'=>$blog->id]) }}">{{ sprintf("%s 全球最新免费HTTP代理IP",date("Y年m月d日H时",strtotime($blog['created_at']))) }}</a>
        </li>
    @endforeach
</ul>
</body>
</html>
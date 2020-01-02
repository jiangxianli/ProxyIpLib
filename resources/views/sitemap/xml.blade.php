<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('web.index',[],false) }}</loc>
        <lastmod>{{ \Carbon\Carbon::now()->format("Y-m-d") }}</lastmod>
        <changefreq>always</changefreq>
        <priority>1.0</priority>
    </url>
    @foreach($countries as $item)
        <url>
            <loc>{{ route("web.country",['area' => $item,'country' => $item],false) }}</loc>
            <lastmod>{{ \Carbon\Carbon::now()->format("Y-m-d") }}</lastmod>
            <changefreq>hourly</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
    @foreach($isp as $item)
        <url>
            <loc>{{ route('web.index',['isp' => $item],false) }}</loc>
            <lastmod>{{ \Carbon\Carbon::now()->format("Y-m-d") }}</lastmod>
            <changefreq>hourly</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
    <url>
        <loc>{{ route('blog.index',[],false) }}</loc>
        <lastmod>{{ \Carbon\Carbon::now()->format("Y-m-d") }}</lastmod>
        <changefreq>hourly</changefreq>
        <priority>0.9</priority>
    </url>
    @foreach ($blogs as $blog)
        <url>
            <loc>{{ route('blog.detail',['blog_id'=>$blog->id],false) }}</loc>
            <lastmod>{{ $blog->updated_at->format("Y-m-d") }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>
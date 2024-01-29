<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($items as $item)
        <url>
            <loc>{{ URL::to('/') }}/item/{{ $item->item_slug }}</loc>
            <lastmod>{{ date('c',time()) }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
</urlset>
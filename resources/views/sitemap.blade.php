<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    @foreach ($staticRouteNames as $routeName => $priority)
        @if (Route::has($routeName))
        <url>
            <loc>{{ route($routeName) }}</loc>
            {{-- এখানে সরাসরি America/New_York ব্যবহার করা হয়েছে --}}
            <lastmod>{{ now()->tz('America/New_York')->toAtomString() }}</lastmod>
            <priority>{{ $priority }}</priority>
        </url>
        @endif
    @endforeach

    @foreach ($blogs as $blog)
    <url>
        <loc>{{ route('blog.details', $blog->slug) }}</loc>
        {{-- ব্লগের আপডেট টাইমজোন কনভার্ট করা --}}
        <lastmod>{{ $blog->updated_at->tz('America/New_York')->toAtomString() }}</lastmod>
        <priority>0.80</priority>
    </url>
    @endforeach

   @foreach ($services as $service)
    @if (!empty($service->slug))
    <url>
        <loc>{{ route('service.details', ['slug' => $service->slug]) }}</loc>
        <lastmod>{{ $service->updated_at->toAtomString() }}</lastmod>
    </url>
    @endif
@endforeach

</urlset>

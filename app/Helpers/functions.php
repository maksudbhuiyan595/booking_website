<?php

use App\Models\BlogPost;
use App\Models\City;
use App\Models\Page;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

if (!function_exists('generate_sitemap_xml')) {
    function generate_sitemap_xml()
    {
        $staticRouteNames = [
            'home'            => '1.00',
            'about'           => '0.80',
            'child.seat'      => '0.80',
            'minivan'         => '0.80',
            'area.we.serve'   => '0.80',
            'contact'         => '0.80',
            'blogs'           => '0.80',
            'privacy.policy'  => '0.80',
            'term.conditions' => '0.80',
            'payment.policy'  => '0.80',
            'area.service'    => '0.80',
        ];
        $blogs = BlogPost::latest()->get();
        $services = Page::all();
        return Response::view('sitemap', [
            'staticRouteNames' => $staticRouteNames,
            'blogs'            => $blogs,
            'services'         => $services,
        ])->header('Content-Type', 'text/xml');
    }
}

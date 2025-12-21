<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Redirect;

class AddTrailingSlash
{
    public function handle(Request $request, Closure $next): Response
    {
        // যদি রিকোয়েস্টটি GET মেথড হয়
        if ($request->isMethod('get')) {

            $path = $request->getPathInfo();

            // যদি পাথ '/' না হয় এবং শেষে '/' না থাকে
            if ($path != '/' && substr($path, -1) != '/') {

                // বর্তমান URL এর সাথে স্ল্যাশ যুক্ত করে রিডাইরেক্ট করা
                // এবং কুয়েরি স্ট্রিং (যেমন ?date=...) থাকলে সেটাও রাখা
                $newUrl = rtrim($request->root(), '/') . $path . '/';

                if ($request->getQueryString()) {
                    $newUrl .= '?' . $request->getQueryString();
                }

                return Redirect::to($newUrl, 301);
            }
        }

        return $next($request);
    }
}

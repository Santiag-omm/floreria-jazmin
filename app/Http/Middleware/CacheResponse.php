<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheResponse
{
    public function handle(Request $request, Closure $next, int $seconds = 60): Response
    {
        if (! $request->isMethod('GET') || auth()->check()) {
            return $next($request);
        }

        $key = 'resp:'.sha1($request->fullUrl());

        $cached = Cache::get($key);
        if (is_array($cached)) {
            return new Response($cached['content'], $cached['status'], $cached['headers']);
        }

        $response = $next($request);

        if ($response->getStatusCode() === 200) {
            $headers = $response->headers->all();
            unset($headers['set-cookie']);

            Cache::put($key, [
                'content' => $response->getContent(),
                'status' => $response->getStatusCode(),
                'headers' => $headers,
            ], $seconds);
        }

        return $response;
    }
}

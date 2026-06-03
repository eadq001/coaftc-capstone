<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::automaticallyEagerLoadRelationships();

//        if (str_starts_with(config('app.url'), 'https://')) {
//            URL::forceScheme('https');
//
//            // Trust forwarded headers from tunnels (ngrok, localtunnel, herd)
//            // Trust all proxies; limit this in production if desired.
//            $trustedHeaders = \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_FOR
//                | \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_HOST
//                | \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PROTO
//                | \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PORT
//                | \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PREFIX;
//
//            Request::setTrustedProxies(
//                ['0.0.0.0/0', '::/0'],
//                $trustedHeaders
//            );
//        }
    }
}

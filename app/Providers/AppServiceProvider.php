<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::automaticallyEagerLoadRelationships();

        Storage::extend('dropbox', function ($app, $config) {
            $client = new DropboxClient($config['token']);

            return new Filesystem(new DropboxAdapter($client));
        });

        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');

            $trustedHeaders = \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_FOR
                | \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_HOST
                | \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PROTO
                | \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PORT
                | \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PREFIX;

            Request::setTrustedProxies(
                ['0.0.0.0/0', '::/0'],
                $trustedHeaders
            );
        }
    }
}

<?php

namespace Yosef\LibyanBadwords;

use Illuminate\Support\ServiceProvider;

class LibyanBadWordsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/libyan_badwords.php' => config_path('libyan_badwords.php')
        ], 'config');
    }

    public function register()
    {
        $this->app->singleton(\Yosef\LibyanBadwords\Filters\LibyanBadWordsFilter::class, function($app) {
            return new \Yosef\LibyanBadwords\Filters\LibyanBadWordsFilter();
        });
    }
}

<?php

namespace Yosef\LibyanBadwords;

use Illuminate\Support\ServiceProvider;
use Yosef\LibyanBadwords\Filters\LibyanBadWordsFilter;

class LibyanBadWordsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/libyan_badwords.php' => config_path('libyan_badwords.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/libyan_badwords.php', 'libyan_badwords');

        $this->app->singleton(LibyanBadWordsFilter::class, function () {
            return new LibyanBadWordsFilter();
        });
    }
}

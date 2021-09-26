<?php

namespace CloudMyn\Bookmark;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class BookmarkServiceProvider extends ServiceProvider
{

    /**
     *  Call when app everything in application is ready
     *  including third-party libraries
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        // publish configuration and migration
        // cmd: php artisan vendor:publish --provider="CloudMyn\Bookmark\BookmarkServiceProvider" --tag="config"
        // cmd: php artisan vendor:publish --provider="CloudMyn\Bookmark\BookmarkServiceProvider" --tag="migrations"
        if ($this->app->runningInConsole()) {

            // publish config file
            $this->publishes([
                __DIR__ . '/../config/bookmark.php' => config_path('bookmark.php'),
            ], 'config');

            // publish migration file
            $this->publishes([
                __DIR__ . '/../migrations/2021_09_20_180958_create_bookmarks_table.php' => database_path('migrations/2021_09_20_180958_create_bookmarks_table.php')
            ], 'migrations');
        }
    }

    /**
     *  Call before application it ready
     *  usefull for registering a singleton
     *  or midleware
     */
    public function register()
    {
    }


    // ...
}

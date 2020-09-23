<?php

namespace M2quared\Analytics;

use Google_Client;
use Illuminate\Support\ServiceProvider;

class AnalyticsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/config/google-analytics.php' => config_path('google-analytics.php')]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('M2quared\Analytics\Analytics', function ($app) {
            if (! \File::exists(config('google-analytics.service_json_path'))) {
                throw new \Exception("Can't find the service .json file in: ".config('google-analytics.service_json_path'));
            }

            $client = new Google_Client();

            $client->setAuthConfig(config('google-analytics.service_json_path'));
            $client->addScope('https://www.googleapis.com/auth/analytics.readonly');

            return new Analytics($client);
        });

        $this->app->singleton('analytics', 'M2quared\Analytics\Analytics');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['analytics'];
    }
}

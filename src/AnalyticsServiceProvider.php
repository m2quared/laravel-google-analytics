<?php

namespace M2quared\Analytics;

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
            if (!\File::exists(config('google-analytics.certificate_path'))) {
                throw new \Exception("Can't find the .p12 certificate in: ".config('google-analytics.certificate_path'));
            }

            $config = [
                'oauth2_client_id' => config('google-analytics.client_id'),
                'use_objects'      => config('google-analytics.use_objects'),
            ];

            $client = new \Google_Client($config);

            $client->setAccessType('offline');

            $client->setAssertionCredentials(
                new \Google_Auth_AssertionCredentials(
                    config('google-analytics.service_email'),
                    ['https://www.googleapis.com/auth/analytics.readonly'],
                    file_get_contents(config('google-analytics.certificate_path'))
                )
            );

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

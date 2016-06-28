# Analytics

Port of Laravel 4 bundle [thujohn/analytics](https://github.com/thujohn/analytics-l4) for Laravel 5

## Installation

Add `m2quared/laravel-google-analytics` to `composer.json`.

    "m2quared/laravel-google-analytics": "^1.0"
    
Run `composer update` to pull down the latest release of Laravel Google Analytics.

Now open up `config/app.php` and add the service provider to your `providers` array.

```php
    'providers' => [
        M2quared\Analytics\AnalyticsServiceProvider::class,
    ]
```

Now add the alias.

```php
    'aliases' => [
        'Analytics' => M2quared\Analytics\AnalyticsFacade::class,
    ]
```


## Configuration

Run `php artisan vendor:publish` and modify the config file `config/google-analytics.php` with your own information.

## Usage
Querying the API for visits and pageviews in the last week.

More information about this calling the Google Analytics API can be found here https://developers.google.com/apis-explorer/#s/analytics/v3/analytics.data.ga.get

A list of all Google Analytics metrics can be found here https://developers.google.com/analytics/devguides/reporting/core/dimsmets

```php
$site_id = Analytics::getSiteIdByUrl('http://github.com/'); // return something like 'ga:11111111'

$stats = Analytics::query($site_id, '7daysAgo', 'yesterday', 'ga:visits,ga:pageviews');
```

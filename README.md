
# Introduction

The **Trackops API Client for PHP** provides an easy to use set of tools to quickly access your [Trackops](https://www.trackops.com) data from any PHP application.

# Installation
[PHP](https://php.net) 5.5+ (compiled with [cURL](http://php.net/manual/en/book.curl.php)) and [Composer](https://getcomposer.org) are required for installation.

If you don't already have composer installed, you can quickly do so by running this command from your project root directory:
```
curl -sS https://getcomposer.org/installer | php
```

Once you have composer, install the [trackops/trackops-client-php package](https://packagist.org/packages/trackops/trackops-client-php) from [Packagist](https://packagist.org/), like so:
```
composer require trackops/trackops-client-php
```

Alternatively, you can manually add trackops/trackops-client-php to your composer.json, like so:
```json
{
    "require": {
        "trackops/trackops-client-php": "~0.2"
    }
}
```
Once the package is successfully installed with composer, install the dependencies like so:
```
composer install
```

# Getting Started
It only takes a few lines of code to get a working example up and running:

**Basic Example of Usage**

Use the `get()` method to retrieve a set of records
```php
// require the composer autoloader
require 'vendor/autoload.php';

use Trackops\Api\Client;

$api = new Client('subdomain', 'username', 'apitoken');
$records = $api->createRequest()->get('cases')->toArray();
```

**Using Query Parameters**

We're passing a `$params` variable with an array of query parameters to this request.
```php
require 'vendor/autoload.php';

use Trackops\Api\Client;

$api = new Client('subdomain', 'username', 'apitoken');
$params = ['from' => '2016-01-01', 'to' => '2016-01-31', 'dir' => 'asc', 'per_page' => 1, 'page' => 1];
$records = $api->createRequest()->get('cases', $params)->toArray();
```
Visit the Trackops [Developer API Reference](https://trackops.zendesk.com/forums/21189735-Developer-API) for a full list of endpoints and query parameters.

**Counting Results**

Use the `count()` method to quickly get the total `record_count` and `page_count` of your query.
```php
require 'vendor/autoload.php';

use Trackops\Api\Client;

$api = new Client('subdomain', 'username', 'apitoken');
$params = ['from' => '2016-01-01', 'to' => '2016-01-31'];
$records = $api->createRequest()->count('cases', $params)->toArray();
```

**Tip:** For more advanced implementations, take a look at the `examples` directory in the project source code.

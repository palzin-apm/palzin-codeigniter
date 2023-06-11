# Real-Time monitoring package using Palzin Monitor (APM) 

[![Latest Stable Version](https://poser.pugx.org/palzin-apm/palzin-codeigniter/v/stable)](https://packagist.org/packages/palzin-apm/palzin-codeigniter)
[![License](https://poser.pugx.org/palzin-apm/palzin-codeigniter/license)](//packagist.org/packages/palzin-apm/palzin-codeigniter)
[![Contributor Covenant](https://img.shields.io/badge/Contributor%20Covenant-2.1-4baaaa.svg)](CODE_OF_CONDUCT.md)


Palzin Monitor offers real-time performance monitoring capabilities that allow you to effectively monitor and analyze the performance of your applications. With Palzin Monitor, you can capture and track all requests without the need for any code modifications. This feature enables you to gain valuable insights into the impact of your methods, database statements, and external requests on the overall user experience.

## Getting Started

To quickly get started with Palzin Monitor (APM) in CodeIgniter, follow these steps:

1. Install the package using Composer by running the following command: `composer require palzin-apm/palzin-codeigniter`.
2. Create a new configuration class in your CodeIgniter project.
3. Copy the provided class from below and paste it into your newly created configuration class.

`Palzin CodeIgniter` is a convenient wrapper around the Palzin Monitor (APM) PHP monitor library, designed specifically for CodeIgniter4 applications.
It simplifies the process of monitoring your code by offering automated inspection functionality without requiring additional code from you.

The library is highly flexible and allows you to customize the automated inspection feature to suit your needs.
You have the power to define your own inspection points, giving you greater control over the monitoring process.
Palzin CodeIgniter can be used in various components of your application, including Controllers, Models, Events, Libraries, and custom classes.
As long as the code has access to CodeIgniter4's services, you can leverage the capabilities of this library.

## Installation

Palzin CodeIgniter can be easily installed via Composer, leveraging CodeIgniter4's autoloading feature. Simply run the following command:

```
composer require palzin-apm/palzin-codeigniter
```

Alternatively, you can manually install the library by downloading the source files and adding the directory to the `app/Config/Autoload.php` file.

## Setup

In order to start using the integration library, you will need to create a config class for it.
`> ./spark make:config Palzin`


```php
<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Palzin extends BaseConfig
{
    /**
     * Set the value to true if you want all your controller methods to be automatically inspected.
     * Set the value to false if you prefer to define your own inspection points, which offers greater flexibility.
     *
     * @var bool
     */
    public $AutoInspect  = true;

    /**
     * To enable sending unhandled exceptions to the Palzin dashboard, 
     * set this option to true. By default, it is set to false for backward compatibility.
     * 
     * @var bool
     */
    public $LogUnhandledExceptions = false;
    
    /**
     * Palzin Monitor (APM) ingestion key, you can find this on your palzin dashboard
     *
     * @var string
     */
    public $PalzinMonitorAPMIngestionKey = 'YOUR_INGESTION_KEY';
    
    /**
     * @var bool
     */
    public $Enable = true;
    
    /**
     * Remote endpoint to send data.
     *
     * @var string
     */
    public $URL = 'https://demo.palzin.app';
    
    /**
     * @var string
     */
    public $Transport = 'async';
    
    /**
     * Transport options.
     *
     * @var array
     */
    public $Options = [];
    
    /**
     * Max numbers of items to collect in a single session.
     *
     * @var int
     */
    public $MaxItems = 100;
}
```

## Usage

To use the Palzin Monitor library integration use the `palzin` service.

```php
$palzinInstance = service('palzin');
```

With AutoInspect set to true, you don't need to do anything else, your application will start being inspected
automatically, this is made possible by the use of CI4 events functionality in the `post_controller_constructor`
the code will start a segment, providing the controller as the title and the method name as the label.
Then in the `post_system` it will end the segment, which means from the start of the incoming request till result
delivery, your code paths will be 'tracked' and the results submitted to palzin.app. And that's it.

You may however need finer grained control over your code points and maybe need to access other more powerful
Palzin Monitor (APM) functionality, and this is where the service comes in. Here we present just a few useful methods,
check the Palzin Monitor (APM) documentation for more methods and features.

You can add a segment from anywhere in your code (assuming this is in your controller method getUsers):

```php
/* gets JSON payload of $limit users */
public function getUsers(int $limit)
{
  return $palzinInstance->addSegment(function() {
    $userModel = new UserModel();
    $users = $userModel->findAll($limit);
    $this->response->setStatusCode(200, 'OK')->setJSON($users, true)->send();
  }, 'getUsers', 'Get Users');
}
```

You can report an exception from anywhere in your code as well (assuming this is your model method, where you validate stuff).
```php
/* validate the user has the proper age set */
public function isActiveAttribute(): bool
{
  try {
    if($this->monitor->active === true) {
      throw new \Exception('Status is active so it means danger.');
    }
  } catch (\Exception $e) {
    $palzinInstance->reportException($e);
    /* Your exception handling code... */
  }
}
```

### Using the Helper

To use the helper, you need to load it first using the helper() method. 
You can do this in your code by calling the helper() method and passing the helper name as an argument. 
Another option is to configure your BaseController to always load the helper.

```php
helper('palzin');

/* get an instance of palzin */
$palzinInstance = palzin();

/* add a segment through the helper */
palzin(function () {
  /* run your code here... */
  $asyncData = $this->checkWebsite('https://doesthissiteworkforyou.test');
  return $this->checkWebsiteSyncJob($asyncData);
}, 'data-load', 'Website Check Flow');

/* add a segment through the instance */
$palzinInstance->addSegment(function ($segment) use ($config) {
            usleep(10 * 1000);

            $segment->addContext('example payload', ['key' => $config->get('palzin.key')]);
        }, 'test', 'Check Palzin Monitor (APM) Ingestion key');

/* set an exception */
$palzinInstance->reportException(new \Exception('First Exception detected using Palzin Monitor (APM)'));
```

> Note: Due to the shorthand nature of the helper function it can only add a segment or return a service instance.

## Official documentation

**[Check out the official documentation](https://palzin.app/guides/codeigniter-introduction)**

## LICENSE

This package is licensed under the [MIT](LICENSE) license.

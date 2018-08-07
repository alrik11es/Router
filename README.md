# Router

[![Join the chat at https://gitter.im/PHPico/Router](https://badges.gitter.im/PHPico/Router.svg)](https://gitter.im/PHPico/Router?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Latest Version](https://img.shields.io/github/release/PHPico/Router.svg?style=flat-square)](https://github.com/PHPico/Router/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/PHPico/Router/master.svg?style=flat-square)](https://travis-ci.org/PHPico/Router)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/PHPico/Router.svg?style=flat-square)](https://scrutinizer-ci.com/g/PHPico/Router/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/PHPico/Router.svg?style=flat-square)](https://scrutinizer-ci.com/g/PHPico/Router)
[![Total Downloads](https://img.shields.io/packagist/dt/PHPico/Router.svg?style=flat-square)](https://packagist.org/packages/PHPico/Router)


The PHPico Router is probably the smallest fully featured
PHP Router for web applications ever built.

It's intended for use with light applications. Even for embbeded uses like
Raspberrypi.

**Disclaimer:** This class could be very useful for absolute noobs.
You could learn A LOT just seeing this library code. Model View Controller is
totally encouraged and is piece of cake if you know how to
use this router. I promise, just one class, just a few lines (Less than 100).

## Features

* Very light
* Intended to be fast
* Manage GET, POST... types for your request
* REGEXP based
* Configuration as array
* Callbacks allowed

## Example code

The next example shows what you can do with this router.

```php
<?php

// include ___DIR__ . '/../vendor/autoload.php';

class HomeController
{
    function index()
    {
        return 'Hello people';
    }

    function greet($a)
    {
        return 'Hello ' . $a;
    }
}

$routes = [
    '/annon' => ['GET', function() {
        return 'Oh yeah callbacks :D';
    }],
    '/greeting/(.*)' => ['GET', 'HomeController@greet'],
    '/book/([0-9]*)' => 'BookController@show',
    '/' => 'HomeController'
];

echo (new \PHPico\Router)->dispatch($routes);
```

### Regular Expressions

You can use [PCRE](http://php.net/PCRE). The only rule is that any REGEXP is launched
as if it has ```/^.......$/``` so you don't need to add the first/end
delimiter and the first/end slashes.

### The basic route

There are multiple ways to create a route. But the basic one is this:

```php
$routes = [
    '/' => 'HomeController'
];

```

HomeController is an example class in the main namespace.
You should add the namespaces if there are any.
If you have newer PHP versions you could use ```php HomeController::class ```

### The callback route

You can use any callable to be used as destination.
For example this anonymous function:

```php
$routes = [
    '/greet/(.*)' => function($a){
        return "Hello ".$a;
    },
];

```

As you can see you can add params in order to get the capture of the REGEXP.
```(.*)``` Means capture any character, zero or more times.
Then just add the ```$a``` var to recover this capture.

### HTTP Request type segmentation

By default any route unless specified will allow any type of request.
But you can segment the route for one or more specific requests. For example:

```php
$routes = [
    '/' => ['POST','HomeController']
];

```

This means that any request to the / path will return false unless you make
a POST request.

You can even add more than one option, like:

```php
$routes = [
    '/' => ['GET', 'POST', 'HomeController']
];

```

### Handling 404

The router component will return false if the route is not found. Simple
and effective.

```php
if ($router->dispatch($routes) === false) {
    header("HTTP/1.0 404 Not Found");
    die('Not found');
}
```

## Have any idea on how to improve?

If you have any idea of how to improve this library you're welcome to
submit a pull request with your improvement or fix. Remember that the
idea is to keep the codebase the smallest possible.

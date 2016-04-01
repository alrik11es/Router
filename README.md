# Router

[![Join the chat at https://gitter.im/PHPico/Router](https://badges.gitter.im/PHPico/Router.svg)](https://gitter.im/PHPico/Router?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

The PHPico Router is probably the smallest PHP Router for web applications ever
built.

It's intended for use with light applications. Even for embbeded uses like
raspberrypi where speed is everything.

##Features

* Very light
* Intended to be fast
* Manage GET|POST... types for your request
* REGEXP based
* Configuration as array
* Callbacks allowed

## Example code

The next example shows what you can do with this router.

```php
<?php
include('../vendor/autoload.php');

class HomeController{
    function index(){
        return 'Hello people';
    }
    
    function greet($a){
        return 'Hello '.$a;
    }
}

$routes = [
    '\/greet\/(.*)' => ['POST', function(){
        return "Oh yeah callbacks :D";
    }],
    '\/greet\/(.*)' => ['GET', 'HomeController@greet'],
    '\/' => 'HomeController'
];

$router = new \PHPico\Router();
echo($router->execute($routes));

```

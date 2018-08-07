<?php

include('../vendor/autoload.php');

class HomeController{

    function index(){
        return 'Hello people <form action="greeting/man" method="POST"><input type="Submit"></form>';
    }

    function greet($a){
        return 'Hello '.$a;
    }

}

class BookController{

    function show($a){
        return 'Book => '.$a;
    }

}

$routes = [
    '\/annon' => ['GET', function(){
        return "Oh yeah callbacks :D";
    }],
    '\/greeting\/(.*)' => ['GET', 'HomeController@greet'],
    '\/book\/([0-9]*)' => 'BookController@show',
    '\/' => 'HomeController'
];

$router = new \PHPico\Router();
echo($router->dispatch($routes));

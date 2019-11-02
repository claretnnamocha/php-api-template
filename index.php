<?php
session_start();

# Autoload files
require 'vendor/autoload.php';

# Setups and confugurations
require 'config.php';

use MiladRahimi\PhpRouter\Router;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;

try {
	$router->group([
		'prefix' => '/',
		'middleware' => []
	],function (Router $router) {
		$router->name('home')->any('','Controller@index');

		$router->name('graphql')->any('graphql/?','Controller@graphql');
	});	
	$router->dispatch();
} catch (RouteNotFoundException $e) {
	header('content-type:application/json');
	die(json_encode([
		'status' => false,
		'message' => '404 Invalid url'
	]));
} catch (Throwable $e) {
	header('content-type:application/json');
	die(json_encode([
		'status' => false,
		'message' => 'Something went wrong!!'
	]));
}
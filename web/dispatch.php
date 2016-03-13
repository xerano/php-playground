<?php
require_once __DIR__ . '/../vendor/autoload.php';


use Pimple\Container;

$app = new Tonic\Application(array(
		'load' => __DIR__ . '/resources/*.php'
));

$app->container = new Container();

$app->container['session_storage'] = function ($c) {
	return new SessionStorage('SESSION_ID');
};

$app->container['session'] = function ($c) {
	return new Session($c['session_storage']);
};

$app->container['db'] = function() {
	$host = 'localhost';
	$dbName = 'statistik';
	$user = 'root';
	$pass = '123456';

	return new \PDO("mysql:host={$host};dbname={$dbName}", $user, $pass);
};



$request = new Tonic\Request();

$resource = $app->getResource($request);
$response = $resource->exec();
$response->output();
<?php
require 'vendor/autoload.php';
$settings = require __DIR__ . '/src/config/settings.php';
$app = new \Slim\App($settings);

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// CarregaDependencias
require __DIR__ . '/src/dependencies.php';

// CarregaRotas
require __DIR__ . '/src/routes.php';


$app->run();

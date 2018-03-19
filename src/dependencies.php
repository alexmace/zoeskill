<?php

use AlexMace\ZoeSkill\Renault\ZeServices;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PhpAmqpLib\Connection\AMQPStreamConnection;
// use PHPUnit\Framework\TestCase;
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['zeservices'] = function ($c) {
    $settings = $c->get('settings')['zeservices'];
    $client = new Client([
        'base_uri'  => $settings['baseUri'],
    ]);
    $ze = new ZeServices($client);

    // Cache the result of this and maybe create the instance of ZeServices
    // using the result?
    $ze->login($settings['email'], $settings['password']);
    return $ze;
};

$container['database'] = function ($c) {
    // Check if the database file exists already, so that we can know to create the
    // database schema if it doesn't exist
    $createTables = false;
    if (!file_exists(dirname(__DIR__) . '/zeservices.db')) {
        $createTables = true;
    }
    $db = new PDO('sqlite:' . dirname(__DIR__) . '/zeservices.db');

    if ($createTables) {
        $sql = '
            CREATE TABLE status (
                chargePercent INTEGER,
                range INTEGER,
                pluggedIn BOOLEAN,
                charging BOOLEAN
            );';
        $db->query($sql);
    }
    return $db;
};

$container['rabbitmq'] = function ($c) {
    $settings = $c->get('settings')['rabbitmq'];
    $connection = new AMQPStreamConnection(
        $settings['hostname'],
        $settings['port'],
        $settings['username'],
        $settings['password']
    );
    return $connection->channel();
};

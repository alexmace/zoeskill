<?php

use AlexMace\ZoeSkill\Renault\ZeServices;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
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

    $container = [];
    $history = Middleware::history($container);

    // Create a mock and queue two responses.
    $mockHandler = new MockHandler([]);

    $stack = HandlerStack::create($mockHandler);
    // Add the history middleware to the handler stack.
    $stack->push($history);

    // Maybe move this into setupResponse function?
    $mockHandler->append(
        new Response(200, [], json_encode(['token' => 'AAAA', 'vehicle_details' => ['VIN' => 'VVVV']])),
        new Response(200, [], json_encode(["charging" => false, "plugged" => true, "charge_level" => 100, "remaining_range" => 124.0, "last_update" => 1476472742000, "charging_point" => "INVALID"]))
    );

    $client = new Client([
        'handler'   => $stack,
        'base_uri'  => $settings['baseUri'],
    ]);
    $ze = new ZeServices($client);

    // Cache the result of this and maybe create the instance of ZeServices
    // using the result?
    $ze->login($settings['email'], $settings['password']);
    return $ze;
};

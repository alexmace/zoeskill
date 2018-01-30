<?php

namespace AlexMace\ZoeSkill\Middleware;

use PHPUnit\Framework\TestCase;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;

class AlexaTest extends TestCase
{
    public function testDummy()
    {
        //$this->markTestIncomplete();

        // Create an instance of the middleware to test
        $middleware = new Alexa();

        // Create a mock environment for testing with
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => 'POST',
                'REQUEST_URI' => 'alexaskillorsomething'
            ]
        );

        // Set up a request object based on the environment
        $request = Request::createFromEnvironment($environment);

        $response = new Response();

        $response = $middleware($request, $response, function ($request, $response) use (&$path) {
            $path = $request->getUri()->getPath();
            return $response;
        });

        $this->assertEquals('BatteryStatus', $path);
    }
    // Inspect to see if request contains an Alexa request

    // If it does, create an instance of Alexa\Request and add it to the
    // request

    // Validate the application ID

    // If not valid, prevent further processing

    // Update the request with a path based on the Intent
}

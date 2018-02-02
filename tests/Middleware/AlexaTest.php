<?php

namespace AlexMace\ZoeSkill\Middleware;

use PHPUnit\Framework\TestCase;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;

class AlexaTest extends TestCase
{

    const EXAMPLE_REQUEST = [
        "version" => "1.0",
        "session" => [
            "new" => true,
            "sessionId" => "amzn1.echo-api.session.44f97423-c395-4091-98fb-7439c617a512",
            "application" => ["applicationId" => "amzn1.ask.skill.cb205333-a429-40ad-89a1-079e287bb5c6"],
            "user" => ["userId" => "amzn1.ask.account.AH2WUV3N7GBF774YBPUAQLKAJCRT3GXGHSYS5OCAP64NLDNZDLRIMGIW6PZGEA2VCMO3D57AMI7VTM45HJ2FPBTT3IITKZU4MSDQYQOLY4BQ5NPDAGM3WYJSA5UBX2ITS2UE3POS7JLCU5VKUXUZHIK6OLAKXLNTH6SYXCQT6IZEQZI7A6YKLC7VX6BI3BN5IGRQRJKKUMQ4QUA"]
        ],
        "context" => [
            "AudioPlayer" => ["playerActivity" => "STOPPED"],
            "System" => [
                "application" => ["applicationId" => "amzn1.ask.skill.cb205333-a429-40ad-89a1-079e287bb5c6"],
                "user" => ["userId" => "amzn1.ask.account.AH2WUV3N7GBF774YBPUAQLKAJCRT3GXGHSYS5OCAP64NLDNZDLRIMGIW6PZGEA2VCMO3D57AMI7VTM45HJ2FPBTT3IITKZU4MSDQYQOLY4BQ5NPDAGM3WYJSA5UBX2ITS2UE3POS7JLCU5VKUXUZHIK6OLAKXLNTH6SYXCQT6IZEQZI7A6YKLC7VX6BI3BN5IGRQRJKKUMQ4QUA"],
                "device" => ["supportedInterfaces" => ["AudioPlayer" => []]]
            ]
        ],
        "request" => [
            "type" => "IntentRequest",
            "requestId" => "amzn1.echo-api.request.6f7edf8c-2002-47e4-af2e-1ab5b0334ab0",
            "timestamp" => "2016-10-22T19:00:43Z",
            "locale" => "en-GB",
            "intent" => ["name" => "StartCleaning"]
        ]
    ];

    // public function testDummy()
    // {
    //     //$this->markTestIncomplete();
    //
    //     // Create an instance of the middleware to test
    //     $middleware = new Alexa();
    //
    //     // Create a mock environment for testing with
    //     $environment = Environment::mock(
    //         [
    //             'REQUEST_METHOD' => 'POST',
    //             'REQUEST_URI' => 'alexaskillorsomething'
    //         ]
    //     );
    //
    //     // Set up a request object based on the environment
    //     $request = Request::createFromEnvironment($environment);
    //
    //     $response = new Response();
    //
    //     $response = $middleware($request, $response, function ($request, $response) use (&$path) {
    //         $path = $request->getUri()->getPath();
    //         return $response;
    //     });
    //
    //     $this->assertEquals('BatteryStatus', $path);
    // }



    // Only attempt to process Alexa requests if it is a POST

    // Inspect to see if request contains an Alexa request

    public function testPostWithAlexaRequestTriggersMiddleware()
    {
        $environment = Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'REQUEST_URI'       => '/irrelevant',
            'HTTP_CONTENT_TYPE' => 'application/json;charset=UTF-8',
        ]);

        $request = Request::createFromEnvironment($environment);

        // Add in a parsed body with an Alexa Request
        $request = $request->withParsedBody(self::EXAMPLE_REQUEST);

        $response = new Response();

        $middleware = new Alexa();

        $response = $middleware($request, $response, function ($request, $response) use (&$path) {
            $path = $request->getUri()->getPath();
            return $response;
        });

        $this->assertEquals('StartCleaning', $path);
    }

    public function testPostWithAlexaRequestButWrongContentTypeDoesNotTrigger()
    {
        $environment = Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'REQUEST_URI'       => '/irrelevant',
            'HTTP_CONTENT_TYPE' => 'text/html;charset=UTF-8',
        ]);

        $request = Request::createFromEnvironment($environment);

        // Add in a parsed body with an Alexa Request
        $request = $request->withParsedBody(self::EXAMPLE_REQUEST);

        $response = new Response();

        $middleware = new Alexa();

        $response = $middleware($request, $response, function ($request, $response) use (&$path) {
            $path = $request->getUri()->getPath();
            return $response;
        });

        $this->assertEquals('/irrelevant', $path);

    }

    public function testPostWithoutAlexaRequestDoesNotTrigger()
    {
        $environment = Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'REQUEST_URI'       => '/irrelevant',
            'HTTP_CONTENT_TYPE' => 'application/json;charset=UTF-8',
        ]);

        $request = Request::createFromEnvironment($environment);

        // Add in a parsed body with an Alexa Request
        $request = $request->withParsedBody([]);

        $response = new Response();

        $middleware = new Alexa();

        $response = $middleware($request, $response, function ($request, $response) use (&$path) {
            $path = $request->getUri()->getPath();
            return $response;
        });

        $this->assertEquals('/irrelevant', $path);
    }

    public function testGetWithAlexaRequestDoesNotTrigger()
    {
        $environment = Environment::mock([
            'REQUEST_METHOD'    => 'GET',
            'REQUEST_URI'       => '/irrelevant',
            'HTTP_CONTENT_TYPE' => 'application/json;charset=UTF-8',
        ]);

        $request = Request::createFromEnvironment($environment);

        // Add in a parsed body with an Alexa Request
        $request = $request->withParsedBody(self::EXAMPLE_REQUEST);

        $response = new Response();

        $middleware = new Alexa();

        $response = $middleware($request, $response, function ($request, $response) use (&$path) {
            $path = $request->getUri()->getPath();
            return $response;
        });

        $this->assertEquals('/irrelevant', $path);
    }

    // If it does, create an instance of Alexa\Request and add it to the
    // request

    // Validate the application ID

    // If not valid, prevent further processing

    // Update the request with a path based on the Intent
}

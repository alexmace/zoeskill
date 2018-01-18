<?php

namespace AlexMace\ZoeSkill\Renault;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

use PHPUnit\Framework\TestCase;

class ZeServicesTest extends TestCase
{
    public function testDummy()
    {
        $this->markTestIncomplete();
    }

    private $container;
    private $mockHandler;
    //private $beehiveApi;

/*
    public function setUp()
    {
        // See http://docs.guzzlephp.org/en/latest/testing.html
        // Create a mock instance of the GuzzleHttp\Client that the class will
        // use to communicate with the API.
        $this->container = [];
        $history = Middleware::history($this->container);

        // Create a mock and queue two responses.
        $this->mockHandler = new MockHandler([]);

        $stack = HandlerStack::create($this->mockHandler);
        // Add the history middleware to the handler stack.
        $stack->push($history);

        $client = new Client(['handler' => $stack,]);

        $this->beehiveApi = new BeehiveApi($client); //, 'serial', 'secret');

    }

    public function testAuthorize()
    {
        $body = [
            'access_token'  => "79228c4d90b331da47abc0ffb2fa0f66",
            'current_time'  => "2017-02-07T10:53:34Z"
        ];

        $headers = [
            'Server'                    => ["Cowboy"],
            'Date'                      => ["Tue, 07 Feb 2017 10:53:34 GMT"],
            'Connection'                => ["keep-alive"],
            'X-Frame-Options'           => ["SAMEORIGIN"],
            'X-Xss-Protection'          => ["1; mode=block"],
            'X-Content-Type-Options'    => ["nosniff"],
            'Content-Type'              => ["application/json; charset=utf-8"],
            'Etag'                      => ["W/\"c69e27a81fd83edc91eb8255a09da678\""],
            'Cache-Control'             => ["max-age=0, private, must-revalidate"],
            'X-Request-Id'              => ["a666d579-c70b-43cf-bc84-56f5a3c4c66c"],
            'X-Runtime'                 => ["0.062680"],
            'Strict-Transport-Security' => ["max-age=15552000"],
            'Vary'                      => ["Origin"],
            'Content-Length'            => ["89"],
            'Via'                       => ["1.1 vegur"],
        ];

        // Maybe move this into setupResponse function?
        $this->mockHandler->append(
            new Response(200, $headers, json_encode($body))
        );

        $this->assertEquals(
            json_decode(json_encode($body)),
            $this->beehiveApi->authorize('user@email.com', 'secretpassword')
        );
        $this->assertCount(1, $this->container);
        $request = $this->container[0]['request'];
        $this->assertEquals('POST', $request->getMethod());

        // Verify that the requst had the required headers
        $headers = $request->getHeaders();
        $this->assertArraySubset(
            [
                'Accept' => ['application/vnd.neato.beehive.v1+json']
            ],
            $headers
        );

        return $this->beehiveApi;
        /*
        $result = NeatoBotvacApi::request($this->baseUrl."/sessions",

        array(
            "platform" 	=> "ios",
            "email" 		=> $email,
            "token" 		=> bin2hex(openssl_random_pseudo_bytes(32)),
            "password" 	=> $password
        )*/ /*
    }*/


    // Guzzle dependency is required

    // Login call we'd expect something like this:
    //  curl \
    // -H "Content-Type: application/json" \
    // -X POST \
    // -d '{"username":"you@example.com","password":"P4ssw0rd"}' \
    // https://www.services.renault-ze.com/api/user/login`
    public function testLogin()
    {

        // Create Guzzle Client we can use for testing.
        $this->container = [];
        $history = Middleware::history($this->container);

        // Create a mock and queue two responses.
        $this->mockHandler = new MockHandler([]);

        $stack = HandlerStack::create($this->mockHandler);
        // Add the history middleware to the handler stack.
        $stack->push($history);

        $client = new Client([
            'handler'   => $stack,
            'base_uri'  => 'https://www.services.renault-ze.com/api/',
        ]);

        // Maybe move this into setupResponse function?
        $this->mockHandler->append(
            new Response(200, [], json_encode(['token' => 'AAAA']))
        );

        $ze = new ZeService($client);
        $token = $ze->login('email@domain.com', 'password');

        // Could later update this to return a user object or something rather
        // then just the token
        $this->assertEquals('AAAA', $token);

        $this->assertCount(1, $this->container);
        $request = $this->container[0]['request'];
        $this->assertEquals('POST', $request->getMethod());
        $this->assertArraySubset(['Content-Type' => ['application/json']], $request->getHeaders());
        $this->assertEquals('https', $request->getUri()->getScheme());
        $this->assertEquals('www.services.renault-ze.com', $request->getUri()->getHost());
        $this->assertEquals('/api/user/login', $request->getUri()->getPath());
    }

    // That will give a response like

    // {
    //     "token": "AAAA",
    //     "refresh_token": "BBBB",
    //     "user": {
    //         "id": "CCCC",
    //         "locale": "en_GB",
    //         "country": "GB",
    //         "timezone": "Europe/London",
    //         "email": "you@example.com",
    //         "first_name": "Terence",
    //         "last_name": "Eden",
    //         "phone_number": "+447700900123",
    //         "vehicle_details": {
    //             "timezone": "Europe/London",
    //             "VIN": "VVVV",
    //             "activation_code": "GGGG",
    //             "phone_number": "+447700900123"
    //         },
    //         "scopes": ["BATTERY_CHARGE_STATUS",
    //                    "BATTERY_CHARGE_HISTORY",
    //                    "BATTERY_CHARGE_REMOTE_ACTIVATION",
    //                    "BATTERY_CHARGE_SCHEDULING",
    //                    "AC_REMOTE_CONTROL",
    //                    "BATTERY_CHARGE_LOWALERT"],
    //         "active_account": "DDDD",
    //         "associated_vehicles": [{
    //             "VIN": "VVVV",
    //             "activation_code": "GGGG",
    //             "user_id": "XXXX"
    //         }],
    //         "gdc_uid": "YYYY"
    //     }
    // }


    // Let's start with the battery. We need to use your token and VIN from above.
    // curl \
    //    -H "Authorization: Bearer AAAA" \
    //    "https://www.services.renault-ze.com/api/vehicle/VVVV/battery"
    // This gets us:
    // {
    //     "charging": false,
    //     "plugged": true,
    //     "charge_level": 100,
    //     "remaining_range": 124.0,
    //     "last_update": 1476472742000,
    //     "charging_point": "INVALID"
    // }
    // A few point to note. The remaining_range is in Kilometres. The last_update is a Unix timestamp.

    // Precondition Now
    // Seems like this should be a POST, and use the content type content-type application/x-www-form-urlencode
    // curl \
    //    -H "Authorization: Bearer AAAA" \
    //    "https://www.services.renault-ze.com/api/vehicle/VVVV/air-conditioning"
    // This command does not return any value. There is also no way to cancel the command remotely - you have to physically enter the car and turn it off.

    // Precondition Later
    // If you know that you want to leave at a specific time, you can set the car to precondition at a set time.
    // curl \
    //    -H "Authorization: Bearer AAAA" \
    //    -H 'Content-Type: application/json;charset=UTF-8' \
    //    --data-binary '{"start":"1753"}' \
    //    'https://www.services.renault-ze.com/api/vehicle/VVVV/air-conditioning/scheduler'

    // Preconditioning Last Status
    // Want to see if the preconditioning message was received by the car correctly?
    // curl \
    //    -H "Authorization: Bearer AAAA" \
    //    "https://www.services.renault-ze.com/api/vehicle/VVVV/air-conditioning/last"
    // This returns information about who or what sent the request:
    // {
    //     "date": 1476538293000,
    //     "type": "USER_REQUEST",
    //     "result": "SUCCESS"
    // }

    // Preconditioning History
    // You can also see how often your car has been preconditioned.
    // curl \
    //    -H "Authorization: Bearer AAAA" \
    //    https://www.services.renault-ze.com/api/vehicle/VVVV/air-conditioning?begin=1016&end=1016
    // [{
    //     "date": 1476165377000,
    //     "type": "USER_REQUEST",
    //     "result": "ERROR"
    // }, {
    //     "date": 1476079325000,
    //     "type": "CAR_NOTIFICATION",
    //     "result": "ERROR"
    // }, {
    //     "date": 1476079270000,
    //     "type": "USER_REQUEST",
    //     "result": "SUCCESS"
    // }, {
    //     "date": 1476079266000,
    //     "type": "CAR_NOTIFICATION",
    //     "result": "SUCCESS"
    // }]

    // Start Charging
    // You may have set your Zoe only to charge at specific times - perhaps to take advantage of cheap rate electricity. You can override this by issuing the charge command.
    // curl \
    //    -H "Authorization: Bearer AAAA" \
    //    https://www.services.renault-ze.com/api/vehicle/VVVV/charge
    // Again, this won't return a response. If your battery cannot be charged, you'll be notified via email or SMS depending on the preference you set up when you registered.

    // Charging History
    // curl \
    //    -H "Authorization: Bearer AAAA" \
    //    https://www.services.renault-ze.com/api/vehicle/VVVV/charge/history?begin=1016&end=1016
    // The begin and end take MMYY as their arguments. That is, if you want October 2016 you need to use 1016.
    // This returns an array, the most recent charging session at the top.
    // [{
    //     "date": 1476538527000,
    //     "type": "START_NOTIFICATION",
    //     "charging_point": "SLOW",
    //     "charge_level": 99,
    //     "remaining_autonomy": 119
    // }, {
    //     "date": 1476472727000,
    //     "type": "END_NOTIFICATION",
    //     "charging_point": "INVALID",
    //     "charge_level": 100,
    //     "remaining_autonomy": 124
    // }, {
    //     "date": 1476462129000,
    //     "type": "START_NOTIFICATION",
    //     "charging_point": "ACCELERATED",
    //     "charge_level": 34,
    //     "remaining_autonomy": 42,
    //     "remaining_time": 10500000
    // }]
    // The remaining_autonomy is, again, the range in Km. The remaining time is expressed in milliseconds. So 10500000 is the equivalent of 2 hours and 55 minutes.

    // Notifications
    // You can use the website to set up notifications. For example, if there is a problem with your charge, Renault will send you an SMS. This API call lets you see what notifications you have set up.
    // Set Notifications
    // curl \
    //    -H "Authorization: Bearer AAAA" \
    //    -X PUT \
    //    -H 'Content-Type: application/json;charset=UTF-8' \
    //    --data-binary '{"battery_status":"EMAIL","charge_start":"SMS","charge_end":"SMS","charge_problem":"SMS","low_battery":"SMS","low_battery_reminder":"SMS","do_not_disturb":null}' \
    //    'https://www.services.renault-ze.com/api/vehicle/VVVV/settings/notification'
    // You can change any of the options with EMAIL or SMS.
    // You can set a "do not disturb" option. This will suppress all notifications during specific times. Sadly, this is a fairly blunt instrument - you can only set one time which then is enforced every day.
    // In the above example, change "do_not_disturb":null to
    // "do_not_disturb":{"start":"1710","end":"1811"}}'
    // This will give you peace between 5:10pm and 6:11pm.

    // See Notifications
    // You can use the website to set up notifications. For example, if there is a problem with your charge, Renault will send you an SMS. This API call lets you see what notifications you have set up.
    // curl \
    //    -H "Authorization: Bearer AAAA" \
    //    https://www.services.renault-ze.com/api/vehicle/VVVV/settings/notification
    // This returns:
    // {
    //     "battery_status": "EMAIL",
    //     "charge_start": "NONE",
    //     "charge_end": "SMS",
    //     "charge_problem": "SMS",
    //     "low_battery": "SMS",
    //     "low_battery_reminder": "SMS",
    //     "do_not_disturb": null
    // }

    // Charging Times
    // The Zoe's charging calendar is, sadly, crap. You can say "charge between these times" but you can only have one schedule per day. So if you only want the car to charge between 0300-0700 and 1800-2200 on Mondays - you're out of luck.
    // It also seemed to force me to set a schedule for every day.
    // This is a multi-stage process.
    // Create a schedule
    // In this example, I'm setting the charging to be active on Monday from 0100 for 1 hour and 15 minutes.
    // All other days start at different times, but last only for 15 minutes.
    // All start times must be at 00, 15, 30, 45 minutes. All durations must be in increments of 15 minutes.
    // curl \
    //    -H 'Authorization: Bearer AAAA' \
    //    -X PUT \
    //    --data-binary '{"optimized_charge":false,"mon":{"start":"0100","duration":"0115"},"tue":{"start":"0200","duration":"0015"},"wed":{"start":"0300","duration":"0015"},"thu":{"start":"1600","duration":"0015"},"fri":{"start":"1900","duration":"0015"},"sat":{"start":"1400","duration":"0015"},"sun":{"start":"1200","duration":"0015"}}' \
    //    'https://www.services.renault-ze.com/api/vehicle/VVVV/charge/scheduler/offboard'

    // View the schedule
    // Let's make sure the schedule has been sent correctly
    // curl \
    //    -H 'Authorization: Bearer AAAA' \
    //    'https://www.services.renault-ze.com/api/vehicle/VVVV/charge/scheduler/onboard'
    // Returned - hopefully! - is the schedule:
    // {
    //     "enabled": false,
    //     "schedule": {
    //         "mon": {
    //             "start": "0100",
    //             "duration": "0115"
    //         },
    //         "tue": {
    //             "start": "0200",
    //             "duration": "0015"
    //         },
    //         "wed": {
    //             "start": "0300",
    //             "duration": "0015"
    //         },
    //         "thu": {
    //             "start": "1600",
    //             "duration": "0015"
    //         },
    //         "fri": {
    //             "start": "1900",
    //             "duration": "0015"
    //         },
    //         "sat": {
    //             "start": "1400",
    //             "duration": "0015"
    //         },
    //         "sun": {
    //             "start": "1200",
    //             "duration": "0015"
    //         }
    //     }
    // }

    // Deploy the schedule
    // Be default, the schedule isn't activated. It needs to be "deployed" in order to send it to the car.
    // curl \
    //    -H 'Authorization: Bearer AAAA' \
    //    -X POST \
    //    'https://www.services.renault-ze.com/api/vehicle/VVVV/charge/scheduler/offboard/deploy'

    // Deactivate the schedule
    // If you deactivate the schedule then the car will charge whenever it is plugged in.
    // curl \
    //    -H 'Authorization: Bearer AAAA' \
    //    -X PUT \
    //    --data-binary '{"enabled":false}' \
    //    'https://www.services.renault-ze.com/api/vehicle/VVVV/charge/scheduler/onboard'
}

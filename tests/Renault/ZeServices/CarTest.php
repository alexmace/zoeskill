<?php

namespace AlexMace\ZoeSkill\Renault\ZeServices;

use PHPUnit\Framework\TestCase;

class CarTest extends TestCase
{
    public function testConstructor()
    {
        //         "vehicle_details": {
        //             "timezone": "Europe/London",
        //             "VIN": "VVVV",
        //             "activation_code": "GGGG",
        //             "phone_number": "+447700900123"
        //         },
        $car = new Car();
        $this->assertTrue($car instanceof Car);
        return $car;
    }

    /**
     * @depends testConstructor
     */
    public function testSetVehicleDetails(Car $car)
    {
        $car->setVehicleDetails([
            'timezone'          => 'Europe/London',
            'VIN'               => 'VVVV',
            'activation_code'   => 'GGGG',
            'phone_number'      => '+447700900123',
        ]);
        return $car;
    }

    /**
     * @depends testSetVehicleDetails
     */
    public function testGetVin(Car $car)
    {
        $this->assertEquals('VVVV', $car->getVin());
    }

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
}

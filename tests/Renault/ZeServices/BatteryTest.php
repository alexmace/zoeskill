<?php

namespace AlexMace\ZoeSkill\Renault\ZeServices;

use PHPUnit\Framework\TestCase;

class BatteryTest extends TestCase
{
    public function testConstructor()
    {
        // {
        // 	"charging": false,
        // 	"plugged": true,
        // 	"charge_level": 100,
        // 	"remaining_range": 124.0,
        // 	"last_update": 1476472742000,
        // 	"charging_point": "INVALID"
        // }
        $battery = new Battery();
        $this->assertTrue($battery instanceof Battery);
        return $battery;
    }

    /**
     * @depends testConstructor
     */
    public function testSetBatteryDetails(Battery $battery)
    {
        $battery->setBatteryDetails([
            "charging"          => false,
            "plugged"           => true,
            "charge_level"      => 100,
            "remaining_range"   => 124.0,
            "last_update"       => 1476472742000,
            "charging_point"    => "INVALID"
        ]);
        return $battery;
    }

    /**
     * @depends testSetBatteryDetails
     */
    public function testGetChargeLevel(Battery $battery)
    {
        $this->assertEquals(100, $battery->getChargeLevel());
    }

    /**
     * @depends testSetBatteryDetails
     */
    public function testGetRangeInMiles(Battery $battery)
    {
        $this->assertEquals(77, $battery->getRangeInMiles());
    }

    /**
     * @depends testSetBatteryDetails
     */
    public function testIsPluggedIn(Battery $battery)
    {
        $this->assertTrue($battery->isPluggedIn());
    }

    /**
     * @depends testSetBatteryDetails
     */
    public function testIsCharging(Battery $battery)
    {
        $this->assertFalse($battery->isCharging());
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

<?php

namespace AlexMace\ZoeSkill\Renault\ZeServices;

class Battery
{
    private $chargeLevel;
    private $charging;
    private $pluggedIn;
    private $rangeInKm;

    public function getChargeLevel()
    {
        return intval(round($this->chargeLevel,0));
    }

    public function getRangeInMiles()
    {
        return intval(round($this->rangeInKm * 0.62137119, 0));
    }

    public function isCharging()
    {
        return $this->charging;
    }

    public function isPluggedIn()
    {
        return $this->pluggedIn;
    }

    public function setBatteryDetails(array $batteryDetails)
    {
        // if (!isset($vehicleDetails['VIN'])) {
        //     throw new InvalidArgumentException('VIN is not available in vehicle details');
        // }
        //
        // $this->vin = $vehicleDetails['VIN'];
        if (!isset($batteryDetails['charging'])) {
            throw new InvalidArgumentException('Charging status is not available in battery details');
        }

        $this->charging = $batteryDetails['charging'];

        if (!isset($batteryDetails['plugged'])) {
            throw new InvalidArgumentException('Plugged in status is not available in battery details');
        }

        $this->pluggedIn = $batteryDetails['plugged'];

        if (!isset($batteryDetails['charge_level'])) {
            throw new InvalidArgumentException('Charge level is not available in battery details');
        }

        $this->chargeLevel = $batteryDetails['charge_level'];

        if (!isset($batteryDetails['remaining_range'])) {
            throw new InvalidArgumentException('Remaining range is not available in battery details');
        }

        $this->rangeInKm = $batteryDetails['remaining_range'];

    }
}

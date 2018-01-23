<?php

namespace AlexMace\ZoeSkill\Renault\ZeServices;

use InvalidArgumentException;

class Car
{
    private $vin;

    public function getVin()
    {
        return $this->vin;
    }

    public function setVehicleDetails(array $vehicleDetails)
    {
        if (!isset($vehicleDetails['VIN'])) {
            throw new InvalidArgumentException('VIN is not available in vehicle details');
        }

        $this->vin = $vehicleDetails['VIN'];
    }
}

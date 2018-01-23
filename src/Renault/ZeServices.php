<?php

namespace AlexMace\ZoeSkill\Renault;

use GuzzleHttp\Client;
use OutOfBoundsException;

class ZeServices
{

    private $car;
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    // Should this accept an argument? or just return the battery for the current car?
    public function getBattery($vin)
    {
        $decodedResponse = $this->request('GET', 'vehicle/' . $vin . '/battery');
        $battery = new ZeServices\Battery();
        $battery->setBatteryDetails($decodedResponse);
        return $battery;
    }

    public function getCar()
    {
        return $this->car;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function login($username, $password)
    {
        $decodedResponse = $this->request('POST', 'user/login', ['username' => $username, 'password' => $password]);

        if (!isset($decodedResponse['vehicle_details'])) {
            throw new OutOfBoundsException('Vehicle details are missing in the response');
        }

        $car = new ZeServices\Car();
        $car->setVehicleDetails($decodedResponse['vehicle_details']);
        $this->car = $car;

        return $decodedResponse['token'];
    }

    private function request($method, $path, $data = null)
    {
        $options = [];
        if (!is_null($data)) {
            $options['json'] = $data;
        }

        $response = $this->client->request($method, $path, $options);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

}

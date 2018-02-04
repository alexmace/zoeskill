<?php

namespace AlexMace\ZoeSkill\Renault;

use GuzzleHttp\Client;
use OutOfBoundsException;

class ZeServices
{

    private $car;
    private $client;
    private $token;

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

        if (!isset($decodedResponse['user']['vehicle_details'])) {
            throw new OutOfBoundsException('Vehicle details are missing in the response');
        }

        $car = new ZeServices\Car();
        $car->setVehicleDetails($decodedResponse['user']['vehicle_details']);
        $this->car = $car;
        $this->token = $decodedResponse['token'];

        return $decodedResponse['token'];
    }

    public function precondition($vin)
    {
        $this->request('POST', 'vehicle/' . $vin . '/air-conditioning');
    }

    private function request($method, $path, $data = null)
    {
        $options = [];
        if (!is_null($data)) {
            $options['json'] = $data;
        }

        if (isset($this->token)) {
            $options['headers'] = [
                'Authorization' => 'Bearer ' . $this->token,
            ];
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

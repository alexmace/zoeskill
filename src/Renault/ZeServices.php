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

    public function sendChargeSchedule(
        $vin,
        $monday = null,
        $tuesday = null,
        $wednesday = null,
        $thursday = null,
        $friday = null,
        $saturday = null,
        $sunday = null
    ) {

        $data = [
            'optimized_charge'  => false,
            // 'mon'               => $monday,
            // 'tue'               => $tuesday,
            // 'wed'               => $wednesday,
            // 'thu'               => $thursday,
            // 'fri'               => $friday,
            // 'sat'               => $saturday,
            // 'sun'               => $sunday,
        ];

        foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
            if (!empty($$day)
                && is_array($$day)
                && count($$day) == 2
                && is_string($$day[0])
                && is_string($$day[1])
                && preg_match('#(2[0-3]|[01][0-9])[0-5][0-9]#', $$day[0])
                && preg_match('#(2[0-3]|[01][0-9])[0-5][0-9]#', $$day[1])
            ) {
                $data[substr($day, 0, 3)] = [
                    'start'     => $$day[0],
                    'duration'  => $$day[1],
                ];
            }
        }

        $this->request(
            'PUT',
            'vehicle/' . $vin . '/charge/scheduler/offboard',
            $data
        );
    }

    // $this->zeServices->sendChargeSchedule(
    //     'VVVV',
    //     ['0130', '0600'], // Monday (start/duration)
    //     ['0130', '0600'], // Tuesday (start/finish)
    //     ['0130', '0600'], // Wednesday (start/finish)
    //     ['0130', '0600'], // Thursday (start/finish)
    //     ['0130', '0600'], // Friday (start/finish)
    //     ['0130', '0600'], // Saturday (start/finish)
    //     ['0130', '0600']  // Sunday (start/finish)
    // );

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

}

<?php

namespace AlexMace\ZoeSkill\Renault;

use GuzzleHttp\Client;

class ZeService
{

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function login($username, $password)
    {
        $response = $this->client->request(
            'POST',
            'user/login',
            [
                'json' => ['username' => $username, 'password' => $password],
            ]
        );
        return json_decode($response->getBody()->getContents())->token;
    }

}

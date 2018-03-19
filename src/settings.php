<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        'zeservices' => [
            'baseUri'  => 'https://www.services.renault-ze.com/api/',
            'email'     => 'email@domain.com',
            'password'  => 'password'
        ],

        'applicationId' => 'amzn1.ask.skill.d504e393-4ef2-465f-8e07-cccc727dc940',

        'rabbitmq' => [
            'hostname'  => 'localhost',
            'port'      => '5672',
            'username'  => 'test',
            'password'  => 'test',
            'vhost'     => '',
            // Perhaps doesn't need to be a setting?
            'queue'     => '',
        ]
    ],
];

<?php

use PhpAmqpLib\Message\AMQPMessage;
use Slim\Http\Request;
use Slim\Http\Response;
use Ramsey\Uuid\Uuid;

// Routes

// Flash Briefing Skill
$app->get('/flash-briefing', function (Request $request, Response $response, array $args) {
    $this->logger->info("Zoe Skill '/flash-briefing' route");

    $sql = 'SELECT * FROM status';
    $statement = $this->database->query($sql);

    foreach ($statement as $row) {
        $mainText = 'Your Zoe currently has a battery charge level of '
                  . $row['chargePercent'] . '% and a range of '
                  . $row['range'] . ' miles. It is '
                  . ($row['pluggedIn'] ? '' : 'not ') . 'plugged in and is '
                  . ($row['charging'] ? '' : 'not ') . 'charging.';
    }

    return $response->withJson([
        "uid"           => "urn:uuid:" . Uuid::uuid4()->toString(),
        "updateDate"    => (new DateTime())->format('Y-m-d\TH:i:s.0\Z'),
        "titleText"     => "Current status of your Renault Zoe",
        "mainText"      => $mainText,
    ]);
});

// Add an ACL or something to this
$app->post('/arrival', function (Request $request, Response $response, array $args) {
    // Endpoint to receive notification from Smartthings and put a message into RMQ
    // to record presence/non-presence of the Zoe
    $channel = $this->rabbitmq;

    // When clocks change, see if time changes in the car. Charging times should
    // be 0030 for 7 hours in GMT. The car switches between GMT and BST though,
    // so this should convert the timings to be correct for tomorrow, since we
    // are assuming that the next charge period at home will be the coming night
    $schedule = [
        ["0130", "0600"] // Monday
        ["0130", "0600"] // Tuesday
        ["0130", "0600"] // Wednesday
        ["0130", "0600"] // Thursday
        ["0130", "0600"] // Friday
        ["0130", "0600"] // Saturday
        ["0130", "0600"] // Sunday
    ];

    $properties = [
        'content_type'  => 'application/json',
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
    ];

    $msg = new AMQPMessage(json_encode(['enableSchedule' => true, 'schedule' => $schedule]), $properties);
    $channel->basic_publish($msg, 'presence', 'arrival');
});

// Add an ACL or something to this
$app->post('/leaving', function (Request $request, Response $response, array $args) {
    // Endpoint to receive notification from Smartthings and put a message into RMQ
    // to record presence/non-presence of the Zoe
    $channel = $this->rabbitmq;

    $properties = [
        'content_type'  => 'application/json',
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
    ];

    $msg = new AMQPMessage(json_encode(['enableSchedule' => false]), $properties);
    $channel->basic_publish($msg, 'presence', 'leaving');
});

$app->post('/Precondition', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("ZoeSkill 'Precondition' route");

    $this->zeservices->precondition($this->zeservices->getCar()->getVin());

    $data = [
        'version' => '1.0',
        'response' => [
            'outputSpeech' => [
                'type' => 'PlainText',
                'text' => 'A pre-condition command has been sent to the Zoe.'
            ],
            'shouldEndSession' => true,
        ],
    ];

    // if (!is_null($cardTitle) && !is_null($cardContent)) {
    //     $data['response']['card'] = [
    //         "type"      => "Simple",
    //         "title"     => $cardTitle,
    //         "content"   => $cardContent,
    //     ];
    // }
    return $response->withJson($data);
});

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

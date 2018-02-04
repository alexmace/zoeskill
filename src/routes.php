<?php

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

$app->get('PreconditionIntent', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("ZoeSkill 'PreconditionIntent' route");

    $this->zeservices->precondition();

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

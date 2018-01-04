<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

// Flash Briefing Skill
$app->get('/flash-briefing', function (Request $request, Response $response, array $args) {
    $this->logger->info("Zoe Skill '/flash-briefing' route");

    return $response->withJson([
        "uid"           => "urn:uuid:1335c695-cfb8-4ebb-abbd-80da344efa6b", // Replace with UUID
        "updateDate"    => "2016-05-23T00:00:00.0Z",
        "titleText"     => "Amazon Developer Blog, week in review May 23rd",
        "mainText"      => "Meet Echosim. A new online community tool for developers that simulates the look and feel of an Amazon Echo.",
    ]);
});

// Report:
// Whether current charging
// Whether plugged in
// Battery charge level
// Remaining range
// Charging point?

// Content-Type: application/json
// ...
// {
//   "uid": "urn:uuid:1335c695-cfb8-4ebb-abbd-80da344efa6b",
//   "updateDate": "2016-05-23T00:00:00.0Z",
//   "titleText": "Amazon Developer Blog, week in review May 23rd",
//   "mainText": "Meet Echosim. A new online community tool for developers that simulates the look and feel of an Amazon Echo.",
//   "redirectionUrl": "https://developer.amazon.com/public/community/blog" // Not required
// }


// Conventional skill

// Start charging

// Get current status

// Start pre-conditioning

// Start pre-conditioning at a time

// Will need to be run from the command line:

// Query Renault periodically to get battery status, range and charging status

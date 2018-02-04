<?php
// Application middleware

$container = $app->getContainer();
$settings = $container->get('settings');

$app->add(new \AlexMace\ZoeSkill\Middleware\Alexa($settings['applicationId']));

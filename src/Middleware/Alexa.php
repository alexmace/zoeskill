<?php

namespace AlexMace\ZoeSkill\Middleware;

class Alexa
{

    public function __invoke($request, $response, $next)
    {
        $uri = $request->getUri();
        $request = $request->withUri($uri->withPath('StartCleaning'));
        // Inspect to see if request contains an Alexa request

        // If it does, create an instance of Alexa\Request and add it to the
        // request

        // Validate the application ID

        // If not valid, prevent further processing

        // Update the request with a path based on the Intent

        return $next($request, $response);
    }
}

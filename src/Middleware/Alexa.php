<?php

namespace AlexMace\ZoeSkill\Middleware;

class Alexa
{

    private $applicationId;

    public function __construct($applicationId)
    {
        $this->applicationId = $applicationId;
    }

    public function __invoke($request, $response, $next)
    {
        $uri = $request->getUri();

        if ($request->getMethod() == 'POST'
            && $request->getMediaType() == 'application/json'
            && $this->isAlexaRequest($request->getParsedBody())
        ) {

            $alexaRequest = $request->getParsedBody();

            if ($alexaRequest['context']['System']['application']['applicationId'] != $this->applicationId) {
                $response = $response->withJson(['errorMessage' => 'Invalid applicationId'], 403);
                return $response;
            }

            $request = $request->withUri($uri->withPath('StartCleaning'));
            // Inspect to see if request contains an Alexa request

            // If it does, create an instance of Alexa\Request and add it to the
            // request

            // Validate the application ID

            // If not valid, prevent further processing

            // Update the request with a path based on the Intent\
        }

        return $next($request, $response);
    }

    private function isAlexaRequest($body)
    {
        // Key contents of a request determined using
        // https://developer.amazon.com/docs/custom-skills/request-and-response-json-reference.html#request-body-syntax
        return (is_array($body) && isset($body['version']) && isset($body['context']) && isset($body['request']));
    }
}

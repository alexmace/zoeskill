<?php

namespace AlexMace\ZoeSkill\Middleware;

use AlexMace\ZoeSkill\Alexa\Request as AlexaRequest;

class Alexa
{

    private $applicationId;
    private $container;

    public function __construct($applicationId, $container)
    {
        $this->applicationId = $applicationId;
        $this->container = $container;
    }

    public function __invoke($request, $response, $next)
    {
        $uri = $request->getUri();

        if ($request->getMethod() == 'POST'
            && $request->getMediaType() == 'application/json'
            && $this->isAlexaRequest($request->getParsedBody())
        ) {

            $alexaRequest = $request->getParsedBody();

            // Log the request at the debug level so we can figure out processing
            // failures
            $this->container->logger->debug('Alexa request received', $alexaRequest);

            if ($alexaRequest['context']['System']['application']['applicationId'] != $this->applicationId) {
                return $response->withJson(['errorMessage' => 'Invalid applicationId'], 403);
            }

            // Create an instance
            $alexaRequestInstance = new AlexaRequest($alexaRequest);

            $request = $request->withUri($uri->withPath($alexaRequestInstance->getIntent()))
                               ->withAttribute('alexaRequest', $alexaRequestInstance);

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

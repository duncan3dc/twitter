<?php

namespace duncan3dc\Twitter;

use duncan3dc\Serial\Json;
use League\Route\Strategy\RequestResponseStrategy;
use Psr\Http\Message\ResponseInterface;

class Dispatcher extends RequestResponseStrategy
{
    /**
     * Attempt to build a response.
     *
     * @param  mixed $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function determineResponse($response)
    {
        if ($response instanceof ResponseInterface) {
            return $response;
        }

        $data = $response;
        $response = $this->getResponse();

        if (is_array($data)) {
            $data = Json::encode($data);
        }

        $response->getBody()->write($data);

        return $response;
    }
}

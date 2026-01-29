<?php

namespace App;

use Slim\Psr7\Response;

class responseFormatter
{

    public function asJson(
        Response $response,
        mixed    $data,
        int      $flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_THROW_ON_ERROR): Response
    {
        $response = $response->withHeader('Content-Type', 'application/json');

        $response->getBody()->write(json_encode($data));
        return $response;
    }
}
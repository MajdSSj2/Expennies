<?php

namespace App\Services;

use App\Contracts\SessionInterface;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequest
{
    public function __construct(public readonly SessionInterface $session)
    {
    }

    public function getReferer(ServerRequestInterface $request): string
    {
        $referer = $request->getHeaderLine('referer')[1] ?? '';

        if(!$referer) {
            $this->session->get('previousUrl');
        }

        $refererHost = parse_url($referer, PHP_URL_HOST);

        if($refererHost !== $request->getUri()->getHost()) {
          $referer = $this->session->get('previousUrl');
        }

        return $referer;
    }
}
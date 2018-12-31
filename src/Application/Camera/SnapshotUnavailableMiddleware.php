<?php

namespace Detroit\Cctv\Application\Camera;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SnapshotUnavailableMiddleware
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $response = $next($request, $response);

        return $response;
    }
}

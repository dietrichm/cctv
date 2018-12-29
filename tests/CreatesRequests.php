<?php

namespace Detroit\Cctv\Tests;

use Slim\Http\Body;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Uri;

trait CreatesRequests
{
    private function createRequest(?string $method = 'GET'): Request
    {
        return new Request(
            'GET',
            Uri::createFromString('http://example.org'),
            new Headers(),
            [],
            [],
            new Body(fopen('php://temp', 'r+'))
        );
    }
}

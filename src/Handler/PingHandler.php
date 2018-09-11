<?php

declare(strict_types=1);

namespace Org_Heigl\KeyPrinter\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class PingHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new JsonResponse(['ack' => time()]);
    }
}

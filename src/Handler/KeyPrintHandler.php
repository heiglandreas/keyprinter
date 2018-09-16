<?php

declare(strict_types=1);

namespace Org_Heigl\KeyPrinter\Handler;

use Org_Heigl\KeyPrinter\Service\FetchGpgKeyDetails;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Plates\PlatesRenderer;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Expressive\ZendView\ZendViewRenderer;

class KeyPrintHandler implements RequestHandlerInterface
{
    private $template;

    private $fetchGpgKeyDetails;

    public function __construct(
        Template\TemplateRendererInterface $template,
        FetchGpgKeyDetails $fetchGpgKeyDetails
    ) {
        $this->template = $template;
        $this->fetchGpgKeyDetails = $fetchGpgKeyDetails;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $serverurl = urldecode($request->getAttribute('keyserver'));
        $keyid     = urldecode($request->getAttribute('keyid'));

        $data = ($this->fetchGpgKeyDetails)($serverurl, $keyid);

        return new HtmlResponse($this->template->render('app::key-print', $data));
    }
}

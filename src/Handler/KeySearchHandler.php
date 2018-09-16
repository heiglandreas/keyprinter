<?php

declare(strict_types=1);

namespace Org_Heigl\KeyPrinter\Handler;

use Org_Heigl\KeyPrinter\Service\FetchGpgKeyDetails;
use Org_Heigl\KeyPrinter\Service\SearchForGpgKeys;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Plates\PlatesRenderer;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Expressive\ZendView\ZendViewRenderer;

class KeySearchHandler implements RequestHandlerInterface
{
    private $urlHelper;

    private $serverUrlHelper;

    private $searcher;

    private $template;

    public function __construct(
        UrlHelper $urlHelper,
        ServerUrlHelper $serverUrlHelper,
        SearchForGpgKeys $searcher,
        Template\TemplateRendererInterface $template
    ) {
        $this->urlHelper = $urlHelper;
        $this->serverUrlHelper = $serverUrlHelper;
        $this->searcher = $searcher;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $query = $request->getQueryParams();

        $results = ($this->searcher)($query['keyserver'], $query['keyid']);

        if (count($results) === 1) {
            $parameters = [
                'keyserver' => urlencode($query['keyserver']),
                'keyid'     => urlencode($results[0]['keyid']),
            ];
            $url        = $this->urlHelper->generate('key.print', $parameters);

            return new RedirectResponse($this->serverUrlHelper->generate($url));
        }

        return new HtmlResponse($this->template->render('app::key-search', [
            'keys' => $results,
            'keyserver' => $query['keyserver'],
            'searchstring' => $query['keyid'],
        ]));


    }
}

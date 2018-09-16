<?php

declare(strict_types=1);

namespace Org_Heigl\KeyPrinter;

use bitExpert\Disco\BeanFactoryRegistry;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;
use bitExpert\Disco\Annotations\Parameter;
use bitExpert\Disco\Annotations\Alias;
use Org_Heigl\KeyPrinter\Filter\ChunkSplit;
use Org_Heigl\KeyPrinter\Filter\HexFormat;
use Org_Heigl\KeyPrinter\Handler\HomePageHandler;
use Org_Heigl\KeyPrinter\Handler\KeyPrintHandler;
use Org_Heigl\KeyPrinter\Handler\KeySearchHandler;
use Org_Heigl\KeyPrinter\Handler\PingHandler;
use Org_Heigl\KeyPrinter\Service\FetchGpgKeyDetails;
use Twig_Environment;
use Twig_Extension;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Zend\Expressive\Container\WhoopsErrorResponseGeneratorFactory;
use Zend\Expressive\Container\WhoopsFactory;
use Zend\Expressive\Container\WhoopsPageHandlerFactory;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Middleware\ErrorResponseGenerator;
use Zend\Expressive\Middleware\WhoopsErrorResponseGenerator;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\Twig\TwigEnvironmentFactory;
use Zend\Expressive\Twig\TwigExtension;
use Zend\Expressive\Twig\TwigExtensionFactory;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Expressive\Twig\TwigRendererFactory;

/**
 * @Configuration
 */
class ConfigProvider
{
    use ManeroConfigTrait;

    /**
     * @Bean({"parameters" = {
     *    @Parameter({"name" = "keyprinter"})
     * }})
     */
    public function config(array $keyprinter = []): array
    {
        $defaultConfig = [
            'debug'                => true,
            'config_cache_enabled' => false,
            'zend-expressive'      => [
                'error_handler' => [
                    'template_404'   => 'error::404',
                    'template_error' => 'error::error',
                ],
            ],
            'templates' => [
                'extension' => 'html.twig',
            ],
            'twig' => [
                'paths'     => [
                    'app'    => 'templates/app',
                    'layout' => 'templates/layout',
                    'error'  => 'templates/error',
                ],
                'cache_dir' => __DIR__ . '/../data/cache',
//                'assets_url' => '',
//                'assets_version' => '',
                'extensions' => [
                  // extension service names or instances
                ],
                'runtime_loaders' => [
                  // runtime loaders names or instances
                ],
                'globals' => [
                  // Global variables passed to twig templates
                  //  'ga_tracking' => 'UA-XXXXX-X'
                ],
                'timezone' => 'UTC',
                'optimizations' => -1, // -1: Enable all (default), 0: disable optimizations
                'autoescape' => 'html', // Auto-escaping strategy [html|js|css|url|false]
            ],
        ];

        return array_merge($defaultConfig, $keyprinter);
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Org_Heigl\KeyPrinter\Handler\HomePageHandler"})
     * }})
     */
    public function getHomePageHandler() : HomePageHandler
    {
        $container = BeanFactoryRegistry::getInstance();
        $router   = $container->get(RouterInterface::class);
        $template = $container->has(TemplateRendererInterface::class)
            ? $container->get(TemplateRendererInterface::class)
            : null;

        return new HomePageHandler($router, $template, get_class($container));
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Org_Heigl\KeyPrinter\Handler\PingHandler"})
     * }})
     */
    public function getPingHandler() : PingHandler
    {
        return new PingHandler();
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Org_Heigl\KeyPrinter\Handler\KeyPrintHandler"})
     * }})
     */
    public function getKeyPrintHandler() : KeyPrintHandler
    {
        $container = BeanFactoryRegistry::getInstance();
        return new KeyPrintHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(FetchGpgKeyDetails::class)
        );
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Org_Heigl\KeyPrinter\Handler\KeySearchHandler"})
     * }})
     */
    public function getKeySearchHandler() : KeySearchHandler
    {
        $container = BeanFactoryRegistry::getInstance();

        return new KeySearchHandler(
            $container->get(UrlHelper::class),
            $container->get(ServerUrlHelper::class)
        );
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Org_Heigl\KeyPrinter\Service\FetchGpgKeyDetails"})
     * }})
     */
    public function getFetchGpgKeyHandler() : FetchGpgKeyDetails
    {
        $container = BeanFactoryRegistry::getInstance();

        return new FetchGpgKeyDetails();
    }



    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Template\TemplateRendererInterface"}),
     *     @Alias({"name" = "Twig_Renderer"})
     * }})
     */
    public function getTemplateRendererInterface() : TemplateRendererInterface
    {
        $factory = new TwigRendererFactory();

        return $factory(BeanFactoryRegistry::getInstance());
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Twig_Environment"})
     * }})
     */
    public function getTwigEnvironment() : Twig_Environment
    {
        $factory = new TwigEnvironmentFactory();

        $env = $factory(BeanFactoryRegistry::getInstance());

        new ChunkSplit($env);
        new HexFormat($env);

        return $env;
    }



    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Twig_Extension"})
     * }})
     */
    public function getTwigExtension() : Twig_Extension
    {
        $factory = new TwigExtensionFactory();

        return $factory(BeanFactoryRegistry::getInstance());
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Twig\TwigExtension"})
     * }})
     */
    public function getTwigExtension2() : TwigExtension
    {
        $factory = new TwigExtensionFactory();

        return $factory(BeanFactoryRegistry::getInstance());
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Middleware\ErrorResponseGenerator"})
     * }})
     */
    public function getErrorResponseGenerator() : WhoopsErrorResponseGenerator
    {
        $factory = new WhoopsErrorResponseGeneratorFactory();

        return $factory(BeanFactoryRegistry::getInstance());
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Whoops\Run"})
     * }})
     */
    public function getWhoops() : Run
    {
        $factory = new WhoopsFactory();

        return $factory(BeanFactoryRegistry::getInstance());
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Whoops\Handler\PrettyPageHandler"})
     * }})
     */
    public function getWhoopsPgeHandler() : PrettyPageHandler
    {
        $factory = new WhoopsPageHandlerFactory();

        return $factory(BeanFactoryRegistry::getInstance());
    }
}

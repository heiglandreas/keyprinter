<?php

declare(strict_types=1);

namespace Org_Heigl\KeyPrinter;

use bitExpert\Disco\BeanFactoryRegistry;
use bitExpert\Disco\Annotations\Alias;
use bitExpert\Disco\Annotations\Bean;
use bitExpert\Disco\Annotations\Parameter;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Container\ApplicationPipelineFactory;
use Zend\Expressive\Container\EmitterFactory;
use Zend\Expressive\Container\ErrorHandlerFactory;
use Zend\Expressive\Container\MiddlewareContainerFactory;
use Zend\Expressive\Container\MiddlewareFactoryFactory;
use Zend\Expressive\Container\NotFoundHandlerFactory;
use Zend\Expressive\Container\RequestHandlerRunnerFactory;
use Zend\Expressive\Container\ResponseFactoryFactory;
use Zend\Expressive\Container\ServerRequestErrorResponseGeneratorFactory;
use Zend\Expressive\Container\ServerRequestFactoryFactory;
use Zend\Expressive\Container\StreamFactoryFactory;
use Zend\Expressive\Container\WhoopsFactory;
use Zend\Expressive\Container\WhoopsPageHandlerFactory;
use Zend\Expressive\Handler\NotFoundHandler;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelperMiddleware;
use Zend\Expressive\Helper\UrlHelperMiddlewareFactory;
use Zend\Expressive\MiddlewareContainer;
use Zend\Expressive\MiddlewareFactory;
use Zend\Expressive\Response\ServerRequestErrorResponseGenerator;
use Zend\Expressive\Router\Middleware\DispatchMiddleware;
use Zend\Expressive\Router\Middleware\DispatchMiddlewareFactory;
use Zend\Expressive\Router\Middleware\ImplicitHeadMiddleware;
use Zend\Expressive\Router\Middleware\ImplicitHeadMiddlewareFactory;
use Zend\Expressive\Router\Middleware\ImplicitOptionsMiddleware;
use Zend\Expressive\Router\Middleware\ImplicitOptionsMiddlewareFactory;
use Zend\Expressive\Router\Middleware\MethodNotAllowedMiddleware;
use Zend\Expressive\Router\Middleware\MethodNotAllowedMiddlewareFactory;
use Zend\Expressive\Router\Middleware\RouteMiddleware;
use Zend\Expressive\Router\Middleware\RouteMiddlewareFactory;
use Zend\Expressive\Router\RouteCollector;
use Zend\Expressive\Router\RouteCollectorFactory;
use Zend\HttpHandlerRunner\Emitter\EmitterInterface;
use Zend\HttpHandlerRunner\RequestHandlerRunner;
use Zend\Stratigility\Middleware\ErrorHandler;
use Zend\Stratigility\MiddlewarePipeInterface;
use Zend\Expressive\Router\FastRouteRouterFactory;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Helper\ServerUrlMiddleware;
use Zend\Expressive\Helper\ServerUrlMiddlewareFactory;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Helper\UrlHelperFactory;
use Closure;

trait ManeroConfigTrait
{
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Router\FastRouteRouter"}),
     *     @Alias({"name" = "Zend\Expressive\Router\RouterInterface"})
     * }})
     */
    public function getZendExpressiveRouterFastRouteRouter() : FastRouteRouter
    {
        return (new FastRouteRouterFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Helper\ServerUrlMiddleware"})
     * }})
     */
    public function getZendExpressiveHelperServerUrlMiddleware() : ServerUrlMiddleware
    {
        return (new ServerUrlMiddlewareFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Helper\UrlHelper"})
     * }})
     */
    public function getZendExpressiveHelperUrlHelper() : UrlHelper
    {
        return (new UrlHelperFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Helper\UrlHelperMiddleware"})
     * }})
     */
    public function getZendExpressiveHelperUrlHelperMiddleware() : UrlHelperMiddleware
    {
        return (new UrlHelperMiddlewareFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Application"})
     * }})
     */
    public function getZendExpressiveApplication() : Application
    {
        return (new ApplicationFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\ApplicationPipeline"})
     * }})
     */
    public function getZendExpressiveApplicationPipeline() : MiddlewarePipeInterface
    {
        return (new ApplicationPipelineFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\HttpHandlerRunner\Emitter\EmitterInterface"})
     * }})
     */
    public function getZendHttpHandlerRunnerEmitterEmitterInterface() : EmitterInterface
    {
        return (new EmitterFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Stratigility\Middleware\ErrorHandler"})
     * }})
     */
    public function getZendStratigilityMiddlewareErrorHandler() : ErrorHandler
    {
        return (new ErrorHandlerFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Handler\NotFoundHandler"}),
     *     @Alias({"name" = "Zend\Expressive\Delegate\DefaultDelegate"}),
     *     @Alias({"name" = "Zend\Expressive\Middleware\NotFoundMiddleware"})
     * }})
     */
    public function getZendExpressiveHandlerNotFoundHandler() : NotFoundHandler
    {
        return (new NotFoundHandlerFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\MiddlewareContainer"})
     * }})
     */
    public function getZendExpressiveMiddlewareContainer() : MiddlewareContainer
    {
        return (new MiddlewareContainerFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\MiddlewareFactory"})
     * }})
     */
    public function getZendExpressiveMiddlewareFactory() : MiddlewareFactory
    {
        return (new MiddlewareFactoryFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\HttpHandlerRunner\RequestHandlerRunner"})
     * }})
     */
    public function getZendHttpHandlerRunnerRequestHandlerRunner() : RequestHandlerRunner
    {
        return (new RequestHandlerRunnerFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Psr\Http\Message\ResponseInterface"})
     * }})
     */
    public function getPsrHttpMessageResponseInterface() : Closure
    {
        return (new ResponseFactoryFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Response\ServerRequestErrorResponseGenerator"})
     * }})
     */
    public function getZERServerRequestErrorResponseGenerator() : ServerRequestErrorResponseGenerator
    {
        return (new ServerRequestErrorResponseGeneratorFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Psr\Http\Message\ServerRequestInterface"})
     * }})
     */
    public function getPsrHttpMessageServerRequestInterface() : Closure
    {
        return (new ServerRequestFactoryFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Psr\Http\Message\StreamInterface"})
     * }})
     */
    public function getPsrHttpMessageStreamInterface() : Closure
    {
        return (new StreamFactoryFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Router\Middleware\DispatchMiddleware"}),
     *     @Alias({"name" = "Zend\Expressive\Middleware\DispatchMiddleware"})
     * }})
     */
    public function getZendExpressiveRouterMiddlewareDispatchMiddleware() : DispatchMiddleware
    {
        return (new DispatchMiddlewareFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Router\Middleware\ImplicitHeadMiddleware"}),
     *     @Alias({"name" = "Zend\Expressive\Middleware\ImplicitHeadMiddleware"})
     * }})
     */
    public function getZERMiddlewareImplicitHeadMiddleware() : ImplicitHeadMiddleware
    {
        return (new ImplicitHeadMiddlewareFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Router\Middleware\ImplicitOptionsMiddleware"}),
     *     @Alias({"name" = "Zend\Expressive\Middleware\ImplicitOptionsMiddleware"})
     * }})
     */
    public function getZERMiddlewareImplicitOptionsMiddleware() : ImplicitOptionsMiddleware
    {
        return (new ImplicitOptionsMiddlewareFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Router\Middleware\MethodNotAllowedMiddleware"})
     * }})
     */
    public function getZERMiddlewareMethodNotAllowedMiddleware() : MethodNotAllowedMiddleware
    {
        return (new MethodNotAllowedMiddlewareFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Router\Middleware\RouteMiddleware"}),
     *     @Alias({"name" = "Zend\Expressive\Middleware\RouteMiddleware"})
     * }})
     */
    public function getZERMiddlewareRouteMiddleware() : RouteMiddleware
    {
        return (new RouteMiddlewareFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Router\RouteCollector"})
     * }})
     */
    public function getZendExpressiveRouterRouteCollector() : RouteCollector
    {
        return (new RouteCollectorFactory())(BeanFactoryRegistry::getInstance());
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Whoops"})
     * }})
     */
    public function getZendExpressiveWhoops() : Run
    {
        return (new WhoopsFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\WhoopsPageHandler"})
     * }})
     */
    public function getZendExpressiveWhoopsPageHandler() : PrettyPageHandler
    {
        return (new WhoopsPageHandlerFactory())(BeanFactoryRegistry::getInstance());
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Helper\ServerUrlHelper"})
     * }})
     */
    public function getZendExpressiveHelperServerUrlHelper() : ServerUrlHelper
    {
        return new ServerUrlHelper();
    }
}

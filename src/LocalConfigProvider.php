<?php

declare(strict_types=1);

namespace Org_Heigl\KeyPrinter;

use bitExpert\Disco\BeanFactoryRegistry;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;
use bitExpert\Disco\Annotations\Parameter;
use bitExpert\Disco\Annotations\Alias;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Zend\Expressive\Container\WhoopsErrorResponseGeneratorFactory;
use Zend\Expressive\Container\WhoopsFactory;
use Zend\Expressive\Container\WhoopsPageHandlerFactory;
use Zend\Expressive\Middleware\WhoopsErrorResponseGenerator;

/**
 * @Configuration
 */
class LocalConfigProvider extends ConfigProvider
{
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

}

<?php

declare(strict_types=1);

use bitExpert\Disco\AnnotationBeanFactory;
use bitExpert\Disco\BeanFactoryConfiguration;
use bitExpert\Disco\BeanFactoryRegistry;

$config     = require __DIR__ . '/config.php';
$beanConfig = new BeanFactoryConfiguration($config['di']['cache']);

// FIXME: Move to caching after finding out where the cache files are stored
// to be able to remove them on deployment!
if (APPLICATION_ENVIRONMENT !== 'production') {
    // Use cached proxies in production
    $beanConfig->setProxyAutoloader(
        new \ProxyManager\Autoloader\Autoloader(
            new \ProxyManager\FileLocator\FileLocator($config['di']['cache']),
            new \ProxyManager\Inflector\ClassNameInflector('Disco')
        )
    );
}

/** @var \Interop\Container\ContainerInterface $container */
$beanFactory = new AnnotationBeanFactory(
    $config['di']['class'],
    ['keyprinter' => $config],
    $beanConfig);
BeanFactoryRegistry::register($beanFactory);

return $beanFactory;

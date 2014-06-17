<?php

namespace Bluetea\ImportBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BlueteaImportExtension extends Extension
{
    private $config;

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->container = $container;

        $configuration = new Configuration();
        $this->config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('parameters.yml');

        $this->handleConfig();
    }

    protected function handleConfig()
    {
        $importClass = $this->config['import_class'];
        $this->container->setParameter('bluetea_import.model.import.class', $importClass);

        $importLogClass = $this->config['import_log_class'];
        $this->container->setParameter('bluetea_import.model.import_log.class', $importLogClass);
    }
}

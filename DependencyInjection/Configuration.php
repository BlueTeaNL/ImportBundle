<?php

namespace Bluetea\ImportBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bluetea_import');

        $rootNode
            ->children()
                ->scalarNode('import_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('import_log_class')->isRequired()->cannotBeEmpty()->end()
                ->integerNode('error_threshold')->defaultValue(1000)->end()
                ->integerNode('progress_update')->defaultValue(1000)->end()
            ->end();

        return $treeBuilder;
    }
}
